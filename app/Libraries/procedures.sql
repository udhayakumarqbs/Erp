DELIMITER //

DROP PROCEDURE IF EXISTS finance_journal_entry //
CREATE PROCEDURE finance_journal_entry(
	IN amount DECIMAL(14,2) ,
	IN debit_account INTEGER(11) ,
	IN credit_account INTEGER(11) ,
	IN narration TEXT ,
	IN created_at VARCHAR(20) ,
	IN created_by INTEGER(11) ,
	IN related_to VARCHAR(140) ,
	IN related_id INTEGER(11) ,
	INOUT debit_id INTEGER(11) ,
	INOUT credit_id INTEGER(11) ,
	INOUT error TINYINT(1)
)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			DECLARE today DATE DEFAULT CURRENT_DATE;
			INSERT INTO journal_entry(gl_acc_id,credit,debit,narration,amount,transaction_date,created_at,created_by,related_to,related_id,prev_amount)
			VALUES(debit_account,0,1,narration,amount,today,created_at,created_by,related_to,related_id,amount) ;
			SELECT LAST_INSERT_ID() INTO debit_id;
			IF debit_id = 0 THEN 
				SET error=1;
			ELSE
				INSERT INTO journal_entry(gl_acc_id,credit,debit,narration,amount,transaction_date,created_at,created_by,related_to,related_id,
				prev_amount) VALUES(credit_account,1,0,narration,amount,today,created_at,created_by,related_to,related_id,amount) ;
				SELECT LAST_INSERT_ID() INTO credit_id;
				IF credit_id = 0 THEN 
					SET error=1;
				END IF;
			END IF;
		END;
END //

DROP PROCEDURE IF EXISTS finance_journal_insert_post //
CREATE PROCEDURE finance_journal_insert_post(
	IN amount DECIMAL(14,2) ,
	IN debit_account INTEGER(11) ,
	IN credit_account INTEGER(11) ,
	IN debit_id INTEGER(11) ,
	IN credit_id INTEGER(11) ,
	INOUT error TINYINT(1)
)
BEGIN
	
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			DECLARE _period DATE ;
			DECLARE checking INTEGER(11) DEFAULT 0;
			SELECT STR_TO_DATE(CONCAT("1",',',MONTH(CURRENT_DATE),',',YEAR(CURRENT_DATE)),'%d,%m,%Y') INTO _period;
			SELECT gl_acc_id INTO checking FROM general_ledger WHERE DATE(period)=DATE(_period) AND gl_acc_id=debit_account;
			IF checking = 0 THEN
				INSERT INTO general_ledger(gl_acc_id,period,actual_amt,balance_fwd) VALUES(debit_account,_period,0.00,0.00);
			END IF;
			UPDATE general_ledger SET actual_amt=actual_amt+amount , balance_fwd=balance_fwd+amount WHERE DATE(period)=DATE(_period) 
			AND gl_acc_id=debit_account;
			UPDATE general_ledger SET balance_fwd=balance_fwd+amount WHERE DATE(period) > DATE(_period) AND gl_acc_id=debit_account; 
			UPDATE journal_entry SET posted=1 , posted_date=CURRENT_DATE WHERE journal_id=debit_id;
			
			SET checking=0;
			SELECT gl_acc_id INTO checking FROM general_ledger WHERE DATE(period)=DATE(_period) AND gl_acc_id=credit_account;
			IF checking = 0 THEN
				INSERT INTO general_ledger(gl_acc_id,period,actual_amt,balance_fwd) VALUES(credit_account,_period,0.00,0.00);
			END IF;
			UPDATE general_ledger SET actual_amt=actual_amt-amount , balance_fwd=balance_fwd-amount WHERE DATE(period)=DATE(_period) 
			AND gl_acc_id=credit_account;
			UPDATE general_ledger SET balance_fwd=balance_fwd-amount WHERE DATE(period) > DATE(_period) AND gl_acc_id=credit_account; 
			UPDATE journal_entry SET posted=1 , posted_date=CURRENT_DATE WHERE journal_id=credit_id;
		END ;
END //

DROP FUNCTION IF EXISTS	finance_automation_insert //
CREATE FUNCTION finance_automation_insert(
	amount DECIMAL(14,2) ,
	debit_account INTEGER(11) ,
	credit_account INTEGER(11) ,
	narration TEXT ,
	created_at VARCHAR(20) ,
	created_by INTEGER(11) ,
	related_to VARCHAR(140) ,
	related_id INTEGER(11) ,
	auto_posting TINYINT(1) 
)  RETURNS INTEGER(11) DETERMINISTIC
BEGIN
	DECLARE error TINYINT(1) DEFAULT 0;
	DECLARE debit_id INTEGER(11) DEFAULT 0;
	DECLARE credit_id INTEGER(11) DEFAULT 0;
	
	CALL finance_journal_entry(
		amount,
		debit_account ,
		credit_account ,
		narration ,
		created_at ,
		created_by ,
		related_to ,
		related_id ,
		debit_id ,
		credit_id ,
		error
	);
	IF error=1 OR debit_id=0 OR credit_id=0 THEN 
		SET error=1;
	ELSE
		IF auto_posting = 1 THEN 
			CALL finance_journal_insert_post(	
				amount ,
				debit_account ,
				credit_account ,
				debit_id ,
				credit_id ,
				error
			);
		END IF;
	END IF;
	RETURN error;
END; //

DROP FUNCTION IF EXISTS finance_automation_update //
CREATE FUNCTION finance_automation_update(
	amount DECIMAL(14,2) ,
	debit_account INTEGER(11) ,
	credit_account INTEGER(11) ,
	narration TEXT ,
	created_at VARCHAR(20) ,
	created_by INTEGER(11) ,
	related_to VARCHAR(140) ,
	related_id INTEGER(11) ,
	auto_posting TINYINT(1) 
)  RETURNS INTEGER(11) DETERMINISTIC
BEGIN
	DECLARE error TINYINT(1) DEFAULT 0;
	DECLARE affected_rows INTEGER(11) DEFAULT 0;
	DECLARE debit_id INTEGER(11) DEFAULT 0;
	DECLARE credit_id INTEGER(11) DEFAULT 0;
	DECLARE db_prev_amount DECIMAL(14,2) DEFAULT 0.00;
	DECLARE db_amount DECIMAL(14,2) DEFAULT 0.00;
	DECLARE db_trans_date DATE;
	DECLARE diff_amt DECIMAL(14,2) DEFAULT 0.00;
	DECLARE _period DATE;
	
	SELECT amount,prev_amount,transaction_date INTO db_amount,db_prev_amount,db_trans_date FROM journal_entry WHERE related_id=related_id AND related_to=related_to LIMIT 1;
	
	UPDATE journal_entry SET amount=amount , prev_amount=db_amount WHERE related_id=related_id AND related_to=related_to;
	SELECT ROW_COUNT() INTO affected_rows;
	IF affected_rows > 0 AND auto_posting = 1 THEN
		SELECT STR_TO_DATE(CONCAT("1",",",MONTH(db_trans_date),',',YEAR(db_trans_date)),"%d,%m,%Y") INTO _period;
		SELECT (db_amount-amount) INTO diff_amt;
		UPDATE general_ledger SET actual_amt=actual_amt-diff_amt , balance_fwd=balance_fwd-diff_amt WHERE gl_acc_id=debit_id AND DATE(period)=DATE(_period) ;
		UPDATE general_ledger SET balance_fwd=balance_fwd-diff_amt WHERE gl_acc_id=debit_id AND DATE(period) > DATE(_period) ;
		UPDATE general_ledger SET actual_amt=actual_amt+diff_amt , balance_fwd=balance_fwd+diff_amt WHERE gl_acc_id=credit_id AND DATE(period)=DATE(_period) ;
		UPDATE general_ledger SET balance_fwd=balance_fwd+diff_amt WHERE gl_acc_id=credit_id AND DATE(period) > DATE(_period) ;
	END IF;
	
	RETURN error;
END; //

DROP FUNCTION IF EXISTS finance_automation_delete //
CREATE FUNCTION finance_automation_delete(
	amount DECIMAL(14,2) ,
	debit_account INTEGER(11) ,
	credit_account INTEGER(11) ,
	narration TEXT ,
	created_at VARCHAR(20) ,
	created_by INTEGER(11) ,
	related_to VARCHAR(140) ,
	related_id INTEGER(11) ,
	auto_posting TINYINT(1) 
)  RETURNS INTEGER(11) DETERMINISTIC
BEGIN
	DECLARE error TINYINT(1) DEFAULT 0;
	DECLARE affected_rows INTEGER(11) DEFAULT 0;
	DECLARE debit_id INTEGER(11) DEFAULT 0;
	DECLARE credit_id INTEGER(11) DEFAULT 0;
	DECLARE db_prev_amount DECIMAL(14,2) DEFAULT 0.00;
	DECLARE db_amount DECIMAL(14,2) DEFAULT 0.00;
	DECLARE db_trans_date DATE;
	DECLARE diff_amt DECIMAL(14,2) DEFAULT 0.00;
	DECLARE _period DATE;
	
	SELECT amount,prev_amount,transaction_date INTO db_amount,db_prev_amount,db_trans_date FROM journal_entry WHERE related_id=related_id AND related_to=related_to LIMIT 1;
	
	DELETE FROM journal_entry WHERE related_id=related_id AND related_to=related_to;
	SELECT ROW_COUNT() INTO affected_rows;
	IF affected_rows > 0 AND auto_posting = 1 THEN
		SELECT STR_TO_DATE(CONCAT("1",",",MONTH(db_trans_date),',',YEAR(db_trans_date)),"%d,%m,%Y") INTO _period;
		SELECT db_amount INTO diff_amt;
		UPDATE general_ledger SET actual_amt=actual_amt-diff_amt , balance_fwd=balance_fwd-diff_amt WHERE gl_acc_id=debit_id AND DATE(period)=DATE(_period) ;
		UPDATE general_ledger SET balance_fwd=balance_fwd-diff_amt WHERE gl_acc_id=debit_id AND DATE(period) > DATE(_period) ;
		UPDATE general_ledger SET actual_amt=actual_amt+diff_amt , balance_fwd=balance_fwd+diff_amt WHERE gl_acc_id=credit_id AND DATE(period)=DATE(_period) ;
		UPDATE general_ledger SET balance_fwd=balance_fwd+diff_amt WHERE gl_acc_id=credit_id AND DATE(period) > DATE(_period) ;
	END IF;
	
	RETURN error;
END //



/*
 CUSTOMFIELD UPDATE FUNCTION
 
 RETURNS 
 0 --> NO OP DONE
 1 --> SUCCESS
 2 --> DUP FOUND
*/
DROP FUNCTION IF EXISTS erp_custom_field_update //
CREATE FUNCTION erp_custom_field_update(w_cf_id INTEGER(11) ,w_related_id INTEGER(11) ,w_field_value VARCHAR(255)) RETURNS TINYINT(1)
BEGIN
	DECLARE ret_code TINYINT(1) DEFAULT 0;
	DECLARE d_field_value VARCHAR(255) DEFAULT '';
	DECLARE affected_rows INTEGER(11) DEFAULT 0;
	DECLARE insert_id INTEGER(11) DEFAULT 0;
	SELECT TRIM(BOTH ' ' FROM field_value) AS field_value INTO d_field_value FROM custom_field_values WHERE cf_id=w_cf_id AND related_id=w_related_id;
	IF d_field_value<>'' THEN
		IF d_field_value=w_field_value THEN
			SET ret_code=2;
		ELSE
			UPDATE custom_field_values SET field_value=w_field_value WHERE cf_id=w_cf_id AND related_id=w_related_id;
			SELECT ROW_COUNT() INTO affected_rows;
			IF affected_rows > 0 THEN
				SET ret_code=1;
			END IF;
		END IF;
	ELSE
		INSERT INTO custom_field_values(cf_id,related_id,field_value) VALUES(w_cf_id,w_related_id,w_field_value) ;
		SELECT LAST_INSERT_ID() INTO insert_id;
		IF insert_id <> 0 THEN
			SET ret_code=1;
		END IF;
	END IF;
	RETURN ret_code;
END //



/*
	SUPPLIER SEGMENT INSERT AND UPDATE
	
	RETURNS 
	0 --> NO OP DONE
	1 --> SUCCESS
*/
DROP FUNCTION IF EXISTS supplier_segment_insert_update //
CREATE FUNCTION supplier_segment_insert_update(w_supplier_id INTEGER(11),w_segment_json TEXT) RETURNS TINYINT(1)
BEGIN
	DECLARE ret_code TINYINT(1) DEFAULT 0;
	DECLARE db_supp_seg_id INTEGER(11) DEFAULT 0;
	DECLARE insert_id INTEGER(11) DEFAULT 0;
	DECLARE affected_rows INTEGER(11) DEFAULT 0;
	
	SELECT supp_seg_id INTO db_supp_seg_id FROM supplier_segment_map WHERE supplier_id=w_supplier_id;
	IF db_supp_seg_id<> 0 THEN
		UPDATE supplier_segment_map SET segment_json=w_segment_json WHERE supp_seg_id=db_supp_seg_id;
		SELECT ROW_COUNT() INTO affected_rows;
		IF affected_rows > 0 THEN
			SET ret_code=1;
		END IF;
	ELSE
		INSERT INTO supplier_segment_map(supplier_id,segment_json) VALUES(w_supplier_id,w_segment_json);
		SELECT LAST_INSERT_ID() INTO insert_id;
		IF insert_id <> 0 THEN
			SET ret_code=1;
		END IF;
	END IF;
	RETURN ret_code;
END//




/*
	INVENTORY REQUISITION INSERT AND UPDATE
	
	 RETURNS 
	 0 --> NO OP DONE
	 1 --> SUCCESS
	 2 --> NO UPDATE
*/
DROP FUNCTION IF EXISTS inventory_requisition_insert_update //
CREATE FUNCTION inventory_requisition_insert_update(w_req_id INTEGER(11),w_related_to VARCHAR(140),
w_related_id INTEGER(11),w_qty INTEGER(11)) RETURNS TINYINT(1)
BEGIN
	DECLARE ret_code TINYINT(1) DEFAULT 0;
	DECLARE db_invent_req_id INTEGER(11) DEFAULT 0;
	DECLARE insert_id INTEGER(11) DEFAULT 0;
	DECLARE affected_rows INTEGER(11) DEFAULT 0;
	
	SELECT invent_req_id INTO db_invent_req_id FROM inventory_requisition WHERE req_id=w_req_id AND related_to=w_related_to AND related_id=w_related_id;
	IF db_invent_req_id<> 0 THEN
		UPDATE inventory_requisition SET qty=w_qty WHERE invent_req_id=db_invent_req_id;
		SELECT ROW_COUNT() INTO affected_rows;
		IF affected_rows > 0 THEN
			SET ret_code=1;
		ELSE
			SET ret_Code=2;
		END IF;
	ELSE
		INSERT INTO inventory_requisition(req_id,related_to,related_id,qty) VALUES(w_req_id,w_related_to,w_related_id,w_qty);
		SELECT LAST_INSERT_ID() INTO insert_id;
		IF insert_id <> 0 THEN
			SET ret_code=1;
		END IF;
	END IF;
	RETURN ret_code;
END //	




/*
	SELECTION RULE SEGMENT INSERT AND UPDATE
	
	RETURNS 
	0 --> NO OP DONE
	1 --> SUCCESS
	2 --> NO UPDATE
*/
DROP FUNCTION IF EXISTS selection_rule_segment //
CREATE FUNCTION selection_rule_segment(w_rule_id INTEGER(11),w_segment_id INTEGER(11),w_above_below TINYINT(1),
w_exclude TINYINT(1),w_segment_value_idx TINYINT(1)) RETURNS TINYINT(1)
BEGIN
	DECLARE ret_code TINYINT(1) DEFAULT 0;
	DECLARE insert_id INTEGER(11) DEFAULT 0;
	DECLARE affected_rows INTEGER(11) DEFAULT 0;
	DECLARE db_rule_seg_id INTEGER(11) DEFAULT 0;
	
	SELECT rule_seg_id INTO db_rule_seg_id FROM selection_rule_segment WHERE rule_id=w_rule_id AND segment_id=w_segment_id;
	IF db_rule_seg_id<> 0 THEN
		UPDATE selection_rule_segment SET above_below=w_above_below , exclude=w_exclude , segment_value_idx=w_segment_value_idx 
		WHERE rule_seg_id=db_rule_seg_id;
				SELECT ROW_COUNT() INTO affected_rows;
		IF affected_rows > 0 THEN
			SET ret_code=1;
		ELSE
			SET ret_Code=2;
		END IF;
	ELSE
		INSERT INTO selection_rule_segment(rule_id,segment_id,above_below,exclude,segment_value_idx) 
		VALUES(w_rule_id,w_segment_id,w_above_below,w_exclude,w_segment_value_idx);
		SELECT LAST_INSERT_ID() INTO insert_id;
		IF insert_id <> 0 THEN
			SET ret_code=1;
		END IF;
	END IF;
	RETURN ret_code;	
END //




/*
	SUPPLIER SEGMENT UPDATE
	
	RETURNS 
	0 --> NO OP DONE
	1 --> SUCCESS
*/
DROP PROCEDURE IF EXISTS supplier_segment_update //
CREATE PROCEDURE supplier_segment_update(IN w_segment_id INTEGER(11),IN w_segment_key VARCHAR(140),IN w_segment_value TEXT)
BEGIN
	DECLARE affected_rows INTEGER(11) DEFAULT 0;
	DECLARE db_segment_key VARCHAR(140) DEFAULT '';
	DECLARE db_segment_value TEXT DEFAULT '';
	DECLARE new_key_len INTEGER(11) DEFAULT 0;
	DECLARE old_key_len INTEGER(11) DEFAULT 0;
	DECLARE diff_len INTEGER(11) DEFAULT 0;
	DECLARE json_path VARCHAR(20) DEFAULT '';

	
	SELECT JSON_LENGTH(w_segment_value) INTO new_key_len;
	SELECT segment_key,segment_value INTO db_segment_key,db_segment_value FROM supplier_segments WHERE segment_id=w_segment_id;
	SELECT JSON_LENGTH(db_segment_value) INTO old_key_len;
	
	SELECT (old_key_len-new_key_len) INTO diff_len;
	UPDATE supplier_segments SET segment_key=w_segment_key , segment_value=w_segment_value WHERE segment_id=w_segment_id;
	
	IF diff_len > 0 THEN
		UPDATE selection_rule_segment SET segment_value_idx=0 WHERE segment_id=w_segment_id AND segment_value_idx>=new_key_len;
		SET json_path='$.';
		SELECT CONCAT(json_path,w_segment_id) INTO json_path;
		UPDATE supplier_segment_map SET segment_json=JSON_REPLACE(segment_json,json_path,"0") WHERE JSON_UNQUOTE(JSON_EXTRACT(segment_json,json_path)) >= new_key_len;
	END IF;
	
END //




/*
	ADD TO STOCK FUNCTION, STOCK QTY UPDATE PROCEDURE
	
	FOR PROCUREMENT MODULE
	RETURNS 
	0 --> SUCCESS
	1 --> ERROR
*/
DROP PROCEDURE IF EXISTS stock_qty_update //
CREATE PROCEDURE stock_qty_update(IN db_related_id INTEGER(11), IN db_related_to VARCHAR(140) , IN db_warehouse_id INTEGER(11) ,
	IN db_price_id INTEGER(11) , IN db_qty INTEGER(11) , INOUT error_flag TINYINT(1) )
BEGIN
	DECLARE insert_id INTEGER(11) DEFAULT 0;
	DECLARE affected_rows INTEGER(11) DEFAULT 0;
	DECLARE db_stock_id INTEGER(11) DEFAULT 0;
	
	SELECT stock_id INTO db_stock_id FROM stocks WHERE warehouse_id=db_warehouse_id AND related_id=db_related_id AND related_to=db_related_to AND price_id=db_price_id;
	IF db_stock_id <> 0 THEN
		UPDATE stocks SET quantity=quantity+db_qty WHERE stock_id=db_stock_id;
		SELECT ROW_COUNT() INTO affected_rows;
		IF affected_rows <= 0 THEN
			SET error_flag=1;
		ELSE
			/* entry_type 0 means procurement */
			INSERT INTO stock_entry(entry_type,stock_id,qty,created_at) VALUES(0,db_stock_id,db_qty,CURRENT_DATE()) ;
		END IF;
	ELSE
		INSERT INTO stocks(related_id,related_to,quantity,price_id,warehouse_id) VALUES(db_related_id,db_related_to,db_qty,db_price_id,db_warehouse_id) ;
		SELECT LAST_INSERT_ID() INTO insert_id;
		IF insert_id <= 0 THEN
			SET error_flag=1;
		ELSE 
			/* entry_type 0 means procurement */
			INSERT INTO stock_entry(entry_type,stock_id,qty,created_at) VALUES(0,insert_id,db_qty,CURRENT_DATE()) ;
		END IF;
	END IF;
END //

DROP FUNCTION IF EXISTS add_to_stock //
CREATE FUNCTION add_to_stock(w_grn_id INTEGER(11)) RETURNS TINYINT(1) 
BEGIN
	DECLARE db_related_to VARCHAR(140);
	DECLARE db_related_id INTEGER(11);
	DECLARE db_warehouse_id INTEGER(11);
	DECLARE db_qty INTEGER(11);
	DECLARE db_price_id INTEGER(11);
	DECLARE error_flag TINYINT(1) DEFAULT 0;
	DECLARE done INTEGER(11) DEFAULT FALSE;
	DECLARE item_cursor CURSOR FOR SELECT related_id,related_to,received_qty,price_id,purchase_order.warehouse_id
		FROM grn JOIN purchase_order ON grn.order_id=purchase_order.order_id JOIN purchase_order_items 
		ON grn.order_id=purchase_order_items.order_id WHERE grn_id=w_grn_id;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done=TRUE;

	OPEN item_cursor;

	label_1: LOOP
		FETCH item_cursor INTO db_related_id,db_related_to,db_qty,db_price_id,db_warehouse_id ;
		IF done THEN
			LEAVE label_1;
		END IF;
		CALL stock_qty_update(db_related_id,db_related_to,db_warehouse_id,db_price_id,db_qty,error_flag);
		IF error_flag = 1 THEN
			LEAVE label_1;
		END IF;
	END LOOP;
	CLOSE item_cursor;
	IF error_flag = 0 THEN
		UPDATE grn SET status=2 WHERE grn_id=w_grn_id;
	END IF;
	RETURN error_flag;
END //

DROP FUNCTION IF EXISTS add_to_stock2 //
CREATE FUNCTION add_to_stock2(w_related_to VARCHAR(140), w_related_id INTEGER(11),w_price_id INTEGER(11),w_warehouse_id INTEGER(11)) RETURNS TINYINT(1)
BEGIN
	DECLARE db_stock_id INTEGER(11) DEFAULT 0;
	DECLARE error TINYINT(1) DEFAULT 0;
	DECLARE affected_rows INTEGER(11) DEFAULT 0;
	DECLARE insert_id INTEGER(11) DEFAULT 0;
	SELECT stock_id INTO db_stock_id FROM stocks WHERE related_to=w_related_to AND related_id=w_related_id AND price_id=w_price_id AND warehouse_id=w_warehouse_id ;
	IF db_stock_id <> 0 THEN
		UPDATE stocks SET quantity=quantity+1 WHERE stock_id=db_stock_id;
		SELECT ROW_COUNT() INTO affected_rows;
		IF affected_rows <= 0 THEN
			SET error=1;
		END IF;
	ELSE 
		INSERT INTO stocks(related_to,related_id,warehouse_id,quantity,price_id) VALUES(w_related_to,w_related_id,w_warehouse_id,1,w_price_id);
		SELECT LAST_INSERT_ID() INTO insert_id;
		IF insert_id <=0 THEN 
			SET error=1;
		END IF;
	END IF;
	IF error=0 THEN
		/* ENTRY TYPE 1 MEANS MANUAL ENTRY */
		INSERT INTO stock_entry(entry_type,stock_id,qty,created_at) VALUES(1,db_stock_id,1,CURRENT_DATE()) ;
		SET insert_id=0;
		SELECT LAST_INSERT_ID() INTO insert_id;
		IF insert_id <=0 THEN
			SET error=1;
		END IF;
	END IF;

	RETURN error;
END //




/*
 Estimate Items for Manufacturing
*/
DROP FUNCTION IF EXISTS estimate_m_items //
CREATE FUNCTION estimate_m_items(w_estimate_id INTEGER(11),w_related_id INTEGER(11),w_quantity INTEGER(11),w_price_id INTEGER(11)) RETURNS TINYINT(1)
BEGIN
	DECLARE db_unit_price DECIMAL(14,2) DEFAULT 0.00;
	DECLARE db_amount DECIMAL(14,2) DEFAULT 0.00;
	DECLARE db_tax1 TINYINT(4);
	DECLARE db_tax2 TINYINT(4);
	DECLARE db_est_item_id INTEGER(11) DEFAULT 0;
	DECLARE error TINYINT(1) DEFAULT 0;
	DECLARE insert_id INTEGER(11) DEFAULT 0;

	SELECT amount,t1.percent,t2.percent INTO db_unit_price,db_tax1,db_tax2 FROM price_list JOIN taxes t1 ON price_list.tax1=t1.tax_id LEFT JOIN taxes t2 ON price_list.tax2=t2.tax_id WHERE price_id=w_price_id;
	SELECT est_item_id INTO db_est_item_id FROM estimate_items WHERE related_to='finished_good' AND related_id=w_related_id AND estimate_id=w_estimate_id;
	SELECT (db_unit_price * w_quantity) INTO db_amount;
	IF db_est_item_id = 0 THEN
		INSERT INTO estimate_items(estimate_id,related_to,related_id,price_id,quantity,unit_price,amount,tax1,tax2) 
		VALUES(w_estimate_id,'finished_good',w_related_id,w_price_id,w_quantity,db_unit_price,db_amount,db_tax1,db_tax2);
		SELECT LAST_INSERT_ID() INTO insert_id;
		IF insert_id <= 0 THEN
			SET error=1;
		END IF;
	ELSE
		UPDATE estimate_items SET price_id=w_price_id , quantity=w_quantity , unit_price=db_unit_price , amount=db_amount , tax1=db_tax1 , tax2=db_tax2 WHERE est_item_id=db_est_item_id;
	END IF;
	RETURN error;
END //


/*
 Quotation, Order, Invoice Items for Manufacturing
*/
DROP FUNCTION IF EXISTS m_sale_order_items //
CREATE FUNCTION m_sale_order_items(w_type VARCHAR(140),w_type_id INTEGER(11),w_related_id INTEGER(11),w_quantity INTEGER(11),w_price_id INTEGER(11)) RETURNS TINYINT(1)
BEGIN
	DECLARE db_unit_price DECIMAL(14,2) DEFAULT 0.00;
	DECLARE db_amount DECIMAL(14,2) DEFAULT 0.00;
	DECLARE db_tax1 TINYINT(4);
	DECLARE db_tax2 TINYINT(4);
	DECLARE db_sale_item_id INTEGER(11) DEFAULT 0;
	DECLARE error TINYINT(1) DEFAULT 0;
	DECLARE insert_id INTEGER(11) DEFAULT 0;
	
	SELECT amount,t1.percent,IFNULL(t2.percent,0) INTO db_unit_price,db_tax1,db_tax2 FROM price_list JOIN taxes t1 ON price_list.tax1=t1.tax_id LEFT JOIN taxes t2 ON price_list.tax2=t2.tax_id WHERE price_id=w_price_id;
	IF w_type="quotation" THEN
		SELECT sale_item_id INTO db_sale_item_id FROM sale_order_items WHERE related_to='finished_good' AND related_id=w_related_id AND quote_id=w_type_id;
	ELSEIF w_type="order" THEN
		SELECT sale_item_id INTO db_sale_item_id FROM sale_order_items WHERE related_to='finished_good' AND related_id=w_related_id AND order_id=w_type_id;
	ELSEIF w_type="invoice" THEN
		SELECT sale_item_id INTO db_sale_item_id FROM sale_order_items WHERE related_to='finished_good' AND related_id=w_related_id AND invoice_id=w_type_id;
	END IF;
	SELECT (db_unit_price * w_quantity) INTO db_amount;
	IF db_sale_item_id = 0 THEN
		IF w_type="quotation" THEN
			INSERT INTO sale_order_items(quote_id,related_to,related_id,price_id,quantity,unit_price,amount,tax1,tax2) 
			VALUES(w_type_id,'finished_good',w_related_id,w_price_id,w_quantity,db_unit_price,db_amount,db_tax1,db_tax2);
		ELSEIF w_type="order" THEN
			INSERT INTO sale_order_items(order_id,related_to,related_id,price_id,quantity,unit_price,amount,tax1,tax2) 
			VALUES(w_type_id,'finished_good',w_related_id,w_price_id,w_quantity,db_unit_price,db_amount,db_tax1,db_tax2);
		ELSEIF w_type="invoice" THEN
			INSERT INTO sale_order_items(invoice_id,related_to,related_id,price_id,quantity,unit_price,amount,tax1,tax2) 
			VALUES(w_type_id,'finished_good',w_related_id,w_price_id,w_quantity,db_unit_price,db_amount,db_tax1,db_tax2);
		END IF;
		SELECT LAST_INSERT_ID() INTO insert_id;
		IF insert_id <= 0 THEN
			SET error=1;
		END IF;
	ELSE
		UPDATE sale_order_items SET price_id=w_price_id , quantity=w_quantity , unit_price=db_unit_price , amount=db_amount , tax1=db_tax1 , tax2=db_tax2 WHERE sale_item_id=db_sale_item_id;
	END IF;
	RETURN error;
END //


/*
  Order, Invoice Items for Construction
*/
DROP FUNCTION IF EXISTS c_sale_order_items //
CREATE FUNCTION c_sale_order_items(w_type VARCHAR(140),w_type_id INTEGER(11),w_related_id INTEGER(11)) RETURNS TINYINT(1)
BEGIN
	DECLARE db_unit_price DECIMAL(14,2) DEFAULT 0.00;
	DECLARE db_amount DECIMAL(14,2) DEFAULT 0.00;
	DECLARE db_tax1 TINYINT(4) DEFAULT 0;
	DECLARE db_tax2 TINYINT(4) DEFAULT 0;
	DECLARE db_sale_item_id INTEGER(11) DEFAULT 0;
	DECLARE error TINYINT(1) DEFAULT 0;
	DECLARE insert_id INTEGER(11) DEFAULT 0;

	SELECT price,t1.percent,IFNULL(t2.percent,0) INTO db_unit_price,db_tax1,db_tax2 FROM property_unit JOIN taxes t1 ON property_unit.tax1=t1.tax_id LEFT JOIN taxes t2 ON property_unit.tax2=t2.tax_id WHERE prop_unit_id=w_related_id;
	SET db_amount=db_unit_price;

	IF w_type="order" THEN
		SELECT sale_item_id INTO db_sale_item_id FROM sale_order_items WHERE related_to='property_unit' AND related_id=w_related_id AND order_id=w_type_id;
	ELSEIF w_type="invoice" THEN
		SELECT sale_item_id INTO db_sale_item_id FROM sale_order_items WHERE related_to='property_unit' AND related_id=w_related_id AND invoice_id=w_type_id;
	END IF;

	IF db_sale_item_id = 0 THEN
		IF w_type="order" THEN
			INSERT INTO sale_order_items(order_id,related_to,related_id,quantity,unit_price,amount,tax1,tax2) 
			VALUES(w_type_id,'property_unit',w_related_id,1,db_unit_price,db_amount,db_tax1,db_tax2);
		ELSEIF w_type="invoice" THEN
			INSERT INTO sale_order_items(invoice_id,related_to,related_id,quantity,unit_price,amount,tax1,tax2) 
			VALUES(w_type_id,'property_unit',w_related_id,1,db_unit_price,db_amount,db_tax1,db_tax2);
		END IF;
		SELECT LAST_INSERT_ID() INTO insert_id;
		IF insert_id <= 0 THEN
			SET error=1;
		END IF;
	ELSE
		UPDATE sale_order_items SET unit_price=db_unit_price , amount=db_amount , tax1=db_tax1 , tax2=db_tax2 WHERE sale_item_id=db_sale_item_id;
	END IF;
	IF error=0 THEN
		UPDATE property_unit SET status=1 WHERE prop_unit_id=w_related_id;
	END IF;

	RETURN error;
END //


/*
	STOCK PICK FOR MANUFACTURING
*/
DROP FUNCTION IF EXISTS m_stock_pick //
CREATE FUNCTION m_stock_pick(w_stock_id INTEGER(11),w_qty_to_pick INTEGER(11),w_related_id INTEGER(11),w_order_id INTEGER(11)) RETURNS TINYINT(1)
BEGIN
	DECLARE error TINYINT(1) DEFAULT 0;
	DECLARE insert_id INTEGER(11) DEFAULT 0;
	DECLARE affected_rows INTEGER(11) DEFAULT 0;
	INSERT INTO stock_entry(entry_type,stock_id,qty,order_id,created_at) VALUES(2,w_stock_id,w_qty_to_pick,w_order_id,CURRENT_DATE());
	SELECT LAST_INSERT_ID() INTO insert_id;
	IF insert_id<> 0 THEN
		UPDATE stocks SET quantity=quantity-w_qty_to_pick WHERE stock_id=w_stock_id;
		SELECT ROW_COUNT() INTO affected_rows;
		IF affected_rows <= 0 THEN
			SET error=1;
		END IF;
	ELSE
		SET error=1;
	END IF;
	RETURN error;
END //



/*
	Payroll Process
*/
DROP FUNCTION IF EXISTS payroll_process //
CREATE FUNCTION payroll_process(w_pay_entry_id INTEGER(11)) RETURNS TINYINT(1)
BEGIN
	DECLARE error TINYINT(1) DEFAULT 0;
	DECLARE db_processed TINYINT(1) DEFAULT 0;
	DECLARE db_pay_from DATE;
	DECLARE db_pay_to DATE;

	DECLARE db_employee_id INTEGER(11) DEFAULT 0;
	DECLARE db_total_w_hours INTEGER(11) DEFAULT 0;
	DECLARE db_total_ot_hours INTEGER(11) DEFAULT 0;
	DECLARE db_w_hr_salary DECIMAL(14,2) DEFAULT 0.00;
	DECLARE db_ot_hr_salary DECIMAL(14,2) DEFAULT 0.00;
	DECLARE db_total_deductions DECIMAL(14,2) DEFAULT 0.00;
	DECLARE db_total_additions DECIMAL(14,2) DEFAULT 0.00;
	DECLARE db_gross_pay DECIMAL(14,2) DEFAULT 0.00;
	DECLARE db_net_pay DECIMAL(14,2) DEFAULT 0.00;
	DECLARE emp_done INTEGER(11) DEFAULT FALSE;
	DECLARE emp_cursor CURSOR FOR SELECT employees.employee_id,IFNULL((SELECT SUM(work_hours) FROM emp_attendance
	WHERE emp_attendance.employee_id=employees.employee_id AND DATE(rec_date) >= DATE(db_pay_from) AND DATE(rec_date) <= DATE(db_pay_to)),0) AS total_w_hr,IFNULL((SELECT SUM(ot_hours) FROM emp_attendance
	WHERE emp_attendance.employee_id=employees.employee_id AND DATE(rec_date) >= DATE(db_pay_from) AND DATE(rec_date) <= DATE(db_pay_to)),0) AS total_ot_hr,
	w_hr_salary*IFNULL((SELECT SUM(work_hours) FROM emp_attendance WHERE emp_attendance.employee_id=employees.employee_id AND DATE(rec_date) >= DATE(db_pay_from) AND DATE(rec_date) <= DATE(db_pay_to)),0) AS total_w_hr_salary,
	ot_hr_salary*IFNULL((SELECT SUM(ot_hours) FROM emp_attendance WHERE emp_attendance.employee_id=employees.employee_id AND DATE(rec_date) >= DATE(db_pay_from) AND DATE(rec_date) <= DATE(db_pay_to)),0) AS total_ot_hr_salary,
	(SELECT SUM(CASE WHEN type=1 THEN value ELSE (total_w_hr * w_hr_salary) *(value/100) END )  FROM payroll_entry 
	JOIN payroll_additions ON payroll_entry.pay_entry_id=payroll_additions.pay_entry_id 
	JOIN additions ON payroll_additions.add_id=additions.add_id WHERE payroll_entry.pay_entry_id=w_pay_entry_id) AS total_additions,
	(SELECT SUM(CASE WHEN type=1 THEN value ELSE (total_w_hr * w_hr_salary) *(value/100) END )  
	FROM payroll_entry JOIN payroll_deductions ON payroll_entry.pay_entry_id=payroll_deductions.pay_entry_id 
	JOIN deductions ON payroll_deductions.deduct_id=deductions.deduct_id WHERE payroll_entry.pay_entry_id=w_pay_entry_id) AS total_deductions
	FROM employees WHERE employees.status=0 ;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET emp_done=TRUE;

	SELECT payment_from,payment_to,processed INTO db_pay_from,db_pay_to,db_processed FROM payroll_entry WHERE pay_entry_id=w_pay_entry_id;
	IF db_processed = 1 THEN
		SET error=1;
	ELSE 
		OPEN emp_cursor;
		emp_loop: LOOP
			FETCH emp_cursor INTO db_employee_id,db_total_w_hours,db_total_ot_hours,db_w_hr_salary,db_ot_hr_salary,db_total_additions,db_total_deductions ;
			IF emp_done THEN 
				LEAVE emp_loop;
			END IF;
			SELECT (db_w_hr_salary+db_ot_hr_salary) INTO db_gross_pay;
			SELECT (db_gross_pay+db_total_additions-db_total_deductions) INTO db_net_pay;
			INSERT INTO payroll_process(pay_entry_id,employee_id,total_w_hours,total_ot_hours,w_hr_salary,ot_hr_salary,gross_pay,total_additions,total_deductions,net_pay) VALUES(w_pay_entry_id,db_employee_id,db_total_w_hours,db_total_ot_hours,db_w_hr_salary,db_ot_hr_salary,db_gross_pay,db_total_additions,db_total_deductions,db_net_pay) ;
		END LOOP ;
		CLOSE emp_cursor ;
		UPDATE payroll_entry SET processed=1 WHERE pay_entry_id=w_pay_entry_id ;
	END IF;
	RETURN error;
END //



/*
	Raw material insert in project
*/
DROP FUNCTION IF EXISTS project_rawmaterial_insert //
CREATE FUNCTION project_rawmaterial_insert(w_related_id INTEGER(11),w_req_qty INTEGER(11),w_project_id INTEGER(11),w_update TINYINT(1)) RETURNS TINYINT(1)
BEGIN
	DECLARE db_project_raw_id INTEGER(11) DEFAULT 0;
	DECLARE error TINYINT(1) DEFAULT 0;
	DECLARE insert_id INTEGER(11) DEFAULT 0;
	DECLARE affected_rows INTEGER(11) DEFAULT 0;

	SELECT project_raw_id INTO db_project_raw_id FROM project_rawmaterials WHERE project_id=w_project_id AND related_id=w_related_id AND req_for_dispatch=0 ;
	IF db_project_raw_id = 0 THEN
		INSERT INTO project_rawmaterials(related_to,related_id,req_qty,project_id) VALUES('raw_material',w_related_id,w_req_qty,w_project_id) ;
		SELECT LAST_INSERT_ID() INTO insert_id;
		IF insert_id <= 0 THEN 
			SET error=1;
		END IF;
	ELSE
		IF w_update = 1 THEN
			UPDATE project_rawmaterials SET req_qty=req_qty+w_req_qty WHERE project_raw_id=db_project_raw_id;
		ELSE
			UPDATE project_rawmaterials SET req_qty=w_req_qty WHERE project_raw_id=db_project_raw_id;
		END IF;
		SELECT ROW_COUNT() INTO affected_rows;
		IF affected_rows <= 0 THEN
			SET error=1;
		END IF;
	END IF;
	RETURN error;
END //

DELIMITER ;