-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2024 at 02:19 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erp`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `finance_journal_entry` (IN `amount` DECIMAL(14,2), IN `debit_account` INT(11), IN `credit_account` INT(11), IN `narration` TEXT, IN `created_at` VARCHAR(20), IN `created_by` INT(11), IN `related_to` VARCHAR(140), IN `related_id` INT(11), INOUT `debit_id` INT(11), INOUT `credit_id` INT(11), INOUT `error` TINYINT(1))   BEGIN
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
END$$

CREATE DEFINER=`stagqbs`@`localhost` PROCEDURE `finance_journal_insert_post` (IN `amount` DECIMAL(14,2), IN `debit_account` INTEGER(11), IN `credit_account` INTEGER(11), IN `debit_id` INTEGER(11), IN `credit_id` INTEGER(11), INOUT `error` TINYINT(1))   BEGIN
	
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
END$$

CREATE DEFINER=`stagqbs`@`localhost` PROCEDURE `stock_qty_update` (IN `db_related_id` INTEGER(11), IN `db_related_to` VARCHAR(140), IN `db_warehouse_id` INTEGER(11), IN `db_price_id` INTEGER(11), IN `db_qty` INTEGER(11), INOUT `error_flag` TINYINT(1))   BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `supplier_segment_update` (IN `w_segment_id` INT(11), IN `w_segment_key` VARCHAR(140), IN `w_segment_value` TEXT)   BEGIN
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
	
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `addOrUpdateProduct` (`w_req_id` INT, `w_related_to` INT, `w_related_id` INT, `w_qty` INT) RETURNS TINYINT(4)  BEGIN
    DECLARE ret_code TINYINT DEFAULT 0;
    DECLARE db_invent_req_id INT DEFAULT 0;
    DECLARE insert_id INT DEFAULT 0;
    DECLARE affected_rows INT DEFAULT 0;

    SELECT invent_req_id INTO db_invent_req_id
    FROM inventory_requisition
    WHERE req_id = w_req_id AND related_to = w_related_to AND related_id = w_related_id;

    IF db_invent_req_id <> 0 THEN
        UPDATE inventory_requisition SET qty = w_qty WHERE invent_req_id = db_invent_req_id;
        SELECT ROW_COUNT() INTO affected_rows;
        IF affected_rows > 0 THEN
            SET ret_code = 1;
        ELSE
            SET ret_code = 2;
        END IF;
    ELSE
        INSERT INTO inventory_requisition (req_id, related_to, related_id, qty)
        VALUES (w_req_id, w_related_to, w_related_id, w_qty);

        SET insert_id = LAST_INSERT_ID();
        IF insert_id <> 0 THEN
            SET ret_code = 1;
        END IF;
    END IF;

    RETURN ret_code;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `add_to_stock` (`w_grn_id` INT(11)) RETURNS TINYINT(1)  BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `add_to_stock2` (`w_related_to` VARCHAR(140), `w_related_id` INT(11), `w_price_id` INT(11), `w_warehouse_id` INT(11)) RETURNS TINYINT(1)  BEGIN
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
END$$

CREATE DEFINER=`stagqbs`@`localhost` FUNCTION `c_sale_order_items` (`w_type` VARCHAR(140), `w_type_id` INTEGER(11), `w_related_id` INTEGER(11)) RETURNS TINYINT(1)  BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `erp_custom_field_update` (`w_cf_id` INT(11), `w_related_id` INT(11), `w_field_value` VARCHAR(255)) RETURNS TINYINT(1)  BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `estimate_m_items` (`w_estimate_id` INT(11), `w_related_id` INT(11), `w_quantity` INT(11), `w_price_id` INT(11)) RETURNS TINYINT(1)  BEGIN
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
END$$

CREATE DEFINER=`stagqbs`@`localhost` FUNCTION `finance_automation_delete` (`amount` DECIMAL(14,2), `debit_account` INTEGER(11), `credit_account` INTEGER(11), `narration` TEXT, `created_at` VARCHAR(20), `created_by` INTEGER(11), `related_to` VARCHAR(140), `related_id` INTEGER(11), `auto_posting` TINYINT(1)) RETURNS INT(11) DETERMINISTIC BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `finance_automation_insert` (`amount` DECIMAL(14,2), `debit_account` INT(11), `credit_account` INT(11), `narration` TEXT, `created_at` VARCHAR(20), `created_by` INT(11), `related_to` VARCHAR(140), `related_id` INT(11), `auto_posting` TINYINT(1)) RETURNS INT(11) DETERMINISTIC BEGIN
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
END$$

CREATE DEFINER=`stagqbs`@`localhost` FUNCTION `finance_automation_update` (`amount` DECIMAL(14,2), `debit_account` INTEGER(11), `credit_account` INTEGER(11), `narration` TEXT, `created_at` VARCHAR(20), `created_by` INTEGER(11), `related_to` VARCHAR(140), `related_id` INTEGER(11), `auto_posting` TINYINT(1)) RETURNS INT(11) DETERMINISTIC BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `inventory_requisition_insert_update` (`w_req_id` INT(11), `w_related_to` VARCHAR(140), `w_related_id` INT(11), `w_qty` INT(11)) RETURNS TINYINT(1)  BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `m_sale_order_items` (`w_type` VARCHAR(140), `w_type_id` INT(11), `w_related_id` INT(11), `w_quantity` INT(11), `w_price_id` INT(11)) RETURNS TINYINT(1)  BEGIN
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
END$$

CREATE DEFINER=`stagqbs`@`localhost` FUNCTION `m_stock_pick` (`w_stock_id` INTEGER(11), `w_qty_to_pick` INTEGER(11), `w_related_id` INTEGER(11), `w_order_id` INTEGER(11)) RETURNS TINYINT(1)  BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `payroll_process` (`w_pay_entry_id` INT(11)) RETURNS TINYINT(1)  BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `project_rawmaterial_insert` (`w_related_id` INT(11), `w_req_qty` INT(11), `w_project_id` INT(11), `w_update` TINYINT(1)) RETURNS TINYINT(1)  BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `remove_to_stock` (`w_related_to` VARCHAR(140), `w_related_id` INT(11), `w_price_id` INT(11), `w_warehouse_id` INT(11)) RETURNS TINYINT(1)  BEGIN
    DECLARE db_stock_id INTEGER(11) DEFAULT 0;
    DECLARE error TINYINT(1) DEFAULT 0;
    DECLARE affected_rows INTEGER(11) DEFAULT 0;
    
    -- Check if a stock record exists for the given parameters
    SELECT stock_id INTO db_stock_id 
    FROM stocks 
    WHERE related_to = w_related_to 
        AND related_id = w_related_id 
        AND price_id = w_price_id 
        AND warehouse_id = w_warehouse_id;

    -- If a stock record exists
    IF db_stock_id <> 0 THEN
        -- Update the quantity of the existing stock record (decrement by 1, assuming it's a delete operation)
        UPDATE stocks SET quantity = quantity - 1 WHERE stock_id = db_stock_id;

        -- Get the number of affected rows after the update
        SELECT ROW_COUNT() INTO affected_rows;

        -- If no rows were affected, set an error flag
        IF affected_rows <= 0 THEN
            SET error = 1;
        END IF;
    ELSE
        -- If no stock record exists, set an error flag
        SET error = 1;
    END IF;

    -- Return the error flag
    RETURN error;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `selection_rule_segment` (`w_rule_id` INT(11), `w_segment_id` INT(11), `w_above_below` TINYINT(1), `w_exclude` TINYINT(1), `w_segment_value_idx` TINYINT(1)) RETURNS TINYINT(1)  BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `supplier_segment_insert_update` (`w_supplier_id` INT(11), `w_segment_json` TEXT) RETURNS TINYINT(1)  BEGIN
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
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `accountbase`
--

CREATE TABLE `accountbase` (
  `base_id` int(11) NOT NULL,
  `base_name` varchar(255) NOT NULL,
  `general_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accountbase`
--

INSERT INTO `accountbase` (`base_id`, `base_name`, `general_name`) VALUES
(1, 'Income', 'revenue'),
(2, 'Overheads', 'expense'),
(3, 'Fixed Assets', 'asset'),
(4, 'Accounts Payable', 'liability'),
(5, 'Accounts Receivable', 'asset'),
(6, 'Current Assets', 'asset');

-- --------------------------------------------------------

--
-- Table structure for table `account_groups`
--

CREATE TABLE `account_groups` (
  `acc_group_id` int(11) NOT NULL,
  `base_id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `profit_loss` tinyint(4) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_groups`
--

INSERT INTO `account_groups` (`acc_group_id`, `base_id`, `group_name`, `profit_loss`, `created_at`, `created_by`) VALUES
(3, 6, 'QBS', 1, '1701952649', 1),
(4, 6, 'softriders', 1, '1702013923', 1),
(8, 1, 'test', 1, '1703764174', 1),
(9, 2, 'Marketing', 1, '1703828104', 1),
(11, 3, 'test', 1, '1717759633', 1);

-- --------------------------------------------------------

--
-- Table structure for table `additions`
--

CREATE TABLE `additions` (
  `add_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `description` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `value` decimal(14,2) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `additions`
--

INSERT INTO `additions` (`add_id`, `name`, `description`, `type`, `value`, `created_at`, `created_by`) VALUES
(1, 'Bonus 1', '', 1, '2000.00', '1651040252', 1),
(2, 'Bonus 2', '', 0, '10.00', '1651040275', 1),
(3, 'T Tax', '5', 1, '5.00', '1703164036', 1);

-- --------------------------------------------------------

--
-- Table structure for table `amenity`
--

CREATE TABLE `amenity` (
  `amenity_id` int(11) NOT NULL,
  `amenity_name` varchar(255) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `amenity`
--

INSERT INTO `amenity` (`amenity_id`, `amenity_name`, `created_at`, `created_by`) VALUES
(1, 'Security', '1648624842', 1),
(2, 'CCTV', '1648624848', 1);

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcementid` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `message` text NOT NULL,
  `showtousers` int(11) NOT NULL,
  `showtostaff` int(11) NOT NULL,
  `showname` int(11) NOT NULL,
  `dateadded` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `userid` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcementid`, `name`, `message`, `showtousers`, `showtostaff`, `showname`, `dateadded`, `updated_at`, `userid`) VALUES
(20, 'Announcement 22', '<p>Today is Tuesday</p>', 0, 1, 0, '2024-05-04 16:58:33', '2024-05-04 16:58:33', 'Admin'),
(21, 'kumar', '<p>hello</p>', 0, 1, 0, '2024-05-04 16:58:47', '2024-05-04 16:58:47', 'Admin'),
(22, 'ashok ', '<p>baiyaa</p>', 0, 1, 0, '2024-05-04 16:59:01', '2024-05-04 16:59:01', 'Admin'),
(23, 'tamil', '<p>vrooo</p>', 0, 1, 0, '2024-05-04 16:59:24', '2024-05-04 16:59:24', 'Admin'),
(25, 'today', '<p>hello</p>', 0, 1, 0, '2024-05-06 11:54:55', '2024-05-06 11:54:55', '1'),
(26, 'q', '<p>q</p>', 0, 1, 0, '2024-05-06 11:55:09', '2024-05-06 11:55:09', '1'),
(28, 'df', '<p>sdf</p>', 0, 1, 0, '2024-05-08 12:16:26', '2024-05-08 12:16:26', '1'),
(29, 'demo', '<p>heloo</p>', 0, 1, 0, '2024-05-08 15:02:51', '2024-05-08 15:02:51', '1'),
(30, 'dsf', '<p>sdf</p>', 0, 1, 0, '2024-05-09 11:48:26', '2024-05-09 11:48:26', '1'),
(31, 'Testing', '', 1, 1, 1, '2024-06-10 12:06:17', '2024-06-10 12:06:17', '1'),
(32, 'Today Day', '<p>Today is Wednesday</p>', 0, 0, 0, '2024-06-11 12:15:39', '2024-06-11 12:15:39', '1'),
(33, 'Announcement 23', '<p>;sa;s;s;s;s;s;;;s;sa;ss;s;s;sa;</p>', 0, 1, 0, '2024-11-19 14:18:15', '2024-11-19 14:18:15', '1');

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `attach_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`attach_id`, `filename`, `related_to`, `related_id`) VALUES
(8, 'journal-book.png', 'lead', 3),
(9, 'Nichiyu_files.xlsx', 'lead', 3),
(11, 'TAG_MANPOWER.docx', 'lead', 3),
(12, 'htdocscounter.png', 'lead', 1),
(13, 'htdocscounter.txt', 'lead', 1),
(16, 'Chrysanthemum.jpg', 'request', 1),
(17, 'cat.png', 'request', 1),
(18, 'enquiry_trigger.txt', 'request', 2),
(19, 'KHSSPA.docx', 'raw_material', 2),
(21, 'Desert.jpg', 'raw_material', 2),
(22, 'export.pdf', 'raw_material', 2),
(24, 'harina_sms_templates.docx', 'finished_good', 2),
(36, 'Staff-Details-Form.docx', 'employee', 1),
(38, 'cat.png', 'contractor', 1),
(39, 'Staff-Details-Form.docx', 'contractor', 1),
(40, 'ContractorImport.csv', 'contractor', 3),
(42, 'export.pdf', 'inventory_service', 1),
(46, 'form3.png', 'ticket', 1),
(51, 'Ads.docx', 'sale_order', 1),
(55, 'form2.png', 'sale_invoice', 2),
(58, 'cat.png', 'equipment', 1),
(62, 'export.pdf', 'lead', 1),
(63, 'Ads.docx', 'project_testing', 1),
(64, 'CREXI_TENX.docx', 'project_testing', 1),
(65, 'Staff-Details-Form.docx', 'project', 3),
(89, 'export(1).xlsx', 'raw_material', 24),
(113, 'rsz_whatsapp_image_2023-11-30_at_185343_0b2b413a.jpg', 'project_testing', 4),
(129, 'manageincome.png', 'credit_note', 2),
(137, 'novsales.xlsx', 'property', 4),
(141, 'file-sample_100kB-Copy.docx', 'contractor', 7),
(142, 'novsales.xlsx', 'contractor', 4),
(156, 'WhatsAppImage2023-12-25at15.07.50_d3f71240.jpg', 'employee', 6),
(163, 'ManufacturingERP.pdf', 'sale_invoice', 3),
(164, 'QBrainstormSoftwareManufacturingCompanyERPDoc.pdf', 'sale_invoice', 3),
(170, 'Outlook_Configuration.docx', 'sale_order', 1),
(171, 'ManufacturingERP.pdf', 'estimate', 3),
(172, 'insta.txt', 'quotation', 1),
(173, 'QBrainstormSoftwareManufacturingCompanyERPDoc.pdf', 'estimate', 4),
(174, 'Outlook_Configuration.docx', 'estimate', 22),
(175, 'export(9).xlsx', 'customer', 6),
(182, 'academicyearselectboxvcvrtoldcode.txt', 'quotation', 1),
(183, 'Massinfra-content.docx', 'semi_finished', 2),
(184, 'consulgurususpiciouscode.txt', 'sale_order', 27),
(186, 'cronss.txt', 'sale_invoice', 1),
(189, 'Massinfra-content.docx', 'purchase_invoice', 2),
(192, 'Massinfra-content.docx', 'project', 2),
(193, 'Massinfra-content.docx', 'equipment', 4),
(195, 'Massinfra-content.docx', 'contractor', 9),
(196, 'AttendanceImport.xlsx', 'ticket', 6),
(199, 'export(2).xlsx', 'semi_finished', 12),
(201, 'post2.png', 'rfq', 8),
(204, 'AttendanceImport(3).xlsx', 'employee', 14),
(207, 'Massinfra-content.docx', 'sale_invoice', 39),
(208, 'QbsSeo.txt', 'sale_invoice', 44),
(210, 'export(12).pdf', 'estimate', 35),
(211, 'export(9).pdf', 'quotation', 5),
(212, 'export(9).pdf', 'sale_order', 31),
(217, 'export(9).pdf', 'team', 15),
(218, 'export(8).pdf', 'project_testing', 11),
(220, 'export(6).pdf', 'equipment', 6),
(225, 'daily-report(3).xlsx', 'project', 23),
(226, 'daily-report.xlsx', 'project', 23),
(227, 'daily-report-m11.xlsx', 'project', 24),
(237, 'daily-report.xlsx', 'contract_Attachment', 10),
(238, 'daily-report-13.xlsx', 'contract_Attachment', 8),
(239, 'daily-report-12.xlsx', 'contract_Attachment', 6),
(240, 'daily-report-m11.xlsx', 'contract_Attachment', 12),
(241, 'daily-report-m14.xlsx', 'contract_Attachment', 13),
(242, 'daily-report-m23.xlsx', 'contract_Attachment', 15),
(243, 'daily-report-m24.xlsx', 'contract_Attachment', 20),
(244, 'daily-report-m29.xlsx', 'contract_Attachment', 22),
(245, 'daily-report-m30.xlsx', 'contract_Attachment', 24),
(246, 'daily-report(3).xlsx', 'contract_Attachment', 25),
(247, 'signed.jpg', 'Expenses_Attachment', 22),
(248, 'notsigned.webp', 'Expenses_Attachment', 24),
(249, 'Cool-8k-Computer-Wallpaper-scaled.jpg', 'Expenses_Attachment', 27),
(250, 'signed.jpg', 'Expenses_Attachment', 30),
(251, 'notsigned.webp', 'Expenses_Attachment', 32),
(254, 'signed.jpg', 'Expenses_Attachment', 35),
(255, 'notsigned.svg', 'sale_invoice', 2),
(256, 'tblexpenses.sql', 'Expenses_Attachment', 36),
(257, 'signed.jpg', 'Expenses_Attachment', 38),
(260, '3138297.png', 'Expenses_Attachment', 40),
(261, 'ERP.docx', 'sale_invoice', 13),
(262, 'contract(1).pdf', 'sale_invoice', 19),
(263, 'Awesome4KUltraHDGalaxyWallpapers-WallpaperAccess.jpeg', 'Expenses_Attachment', 41),
(264, 'wall.jpg', 'sale_invoice', 20),
(265, 'contract(3).pdf', 'Expenses_Attachment', 42),
(266, 'wilfred_1st_poster.png', 'Expenses_Attachment', 43),
(267, 'ldoe.webp', 'Expenses_Attachment', 45);

-- --------------------------------------------------------

--
-- Table structure for table `auto_transaction`
--

CREATE TABLE `auto_transaction` (
  `autotrans_id` int(11) NOT NULL,
  `trans_id` int(11) NOT NULL,
  `debit_gl_account` int(11) NOT NULL,
  `credit_gl_account` int(11) NOT NULL,
  `auto_posting` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auto_transaction`
--

INSERT INTO `auto_transaction` (`autotrans_id`, `trans_id`, `debit_gl_account`, `credit_gl_account`, `auto_posting`, `active`) VALUES
(6, 1, 1, 2, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `auto_trans_list`
--

CREATE TABLE `auto_trans_list` (
  `trans_id` int(11) NOT NULL,
  `transaction_name` varchar(140) NOT NULL,
  `related_to` varchar(140) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auto_trans_list`
--

INSERT INTO `auto_trans_list` (`trans_id`, `transaction_name`, `related_to`) VALUES
(1, 'Marketing Expense', 'marketing');

-- --------------------------------------------------------

--
-- Table structure for table `bankaccounts`
--

CREATE TABLE `bankaccounts` (
  `bank_id` int(11) NOT NULL,
  `gl_acc_id` int(11) NOT NULL,
  `bank_name` varchar(140) NOT NULL,
  `bank_acc_no` varchar(40) NOT NULL DEFAULT '',
  `bank_code` varchar(255) NOT NULL,
  `branch` varchar(140) NOT NULL,
  `address` varchar(500) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bankaccounts`
--

INSERT INTO `bankaccounts` (`bank_id`, `gl_acc_id`, `bank_name`, `bank_acc_no`, `bank_code`, `branch`, `address`, `created_at`, `created_by`) VALUES
(1, 2, 'TKD Bank', '1234567890123', '1234', 'chennai', 'No.164, First Floor, Arcot Rd, Valasaravakkam', '1645264242', 1),
(2, 2, 'State', '1234567890123', '659850', 'chennai', 'No.164, First Floor, Arcot Rd, Valasaravakkam', '1645264266', 1),
(3, 1, 'state', '12454', 'IOB234', 'valasarvakkam', '12', '1703333072', 1),
(4, 15, 'testi', '123', '23', 'test', '12', '1703333140', 1),
(5, 1, 'TKD Bank', '12454', '1234', 'chennai', 'madurai', '1705750080', 1);

-- --------------------------------------------------------

--
-- Table structure for table `bom`
--

CREATE TABLE `bom` (
  `bom_id` int(11) NOT NULL,
  `related_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `skucode` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `loss_percentage` int(11) DEFAULT NULL,
  `loss_qty` int(11) DEFAULT NULL,
  `operation_per_unit_cost` double NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bom`
--

INSERT INTO `bom` (`bom_id`, `related_id`, `product_id`, `skucode`, `quantity`, `amount`, `date`, `warehouse_id`, `status`, `loss_percentage`, `loss_qty`, `operation_per_unit_cost`, `created_at`, `updated_at`) VALUES
(26, 1, 25, 'PD10', 1, 1305, '2024-12-10', 1, 0, NULL, NULL, 0, '2024-12-10 19:25:39', '2024-12-10 19:25:39'),
(27, 1, 25, 'PD10', 1, 1400, '2024-12-11', 2, 1, NULL, NULL, 0, '2024-12-11 10:50:46', '2024-12-11 10:50:46'),
(28, 13, 26, 'SKU0026', 1, 1100, '2024-12-12', 1, 1, NULL, NULL, 0, '2024-12-12 14:26:00', '2024-12-12 14:26:00'),
(29, 13, 26, 'SKU0026', 1, 264, '2024-12-12', 1, 0, NULL, NULL, 0, '2024-12-12 14:27:54', '2024-12-12 14:27:54'),
(30, 1, 25, 'PD10', 1, 100, '2024-12-12', 1, 0, NULL, NULL, 0, '2024-12-12 14:47:02', '2024-12-12 14:47:02'),
(31, 1, 25, 'PD10', 1001, 486, '2024-12-12', 1, 2, 0, 0, 0, '2024-12-12 19:19:18', '2024-12-12 19:19:18'),
(32, 1, 25, 'PD10', 1, 144, '2024-12-14', 2, 1, 0, 0, 0, '2024-12-14 10:20:22', '2024-12-14 10:20:22'),
(33, 1, 25, 'PD10', 1, 144, '2024-12-14', 2, 1, 0, 0, 0, '2024-12-14 10:23:31', '2024-12-14 10:23:31'),
(34, 1, 25, 'PD10', 1, 24, '2024-12-14', 2, 0, 12, 0, 0, '2024-12-14 10:25:17', '2024-12-14 10:25:17'),
(35, 1, 25, 'PD10', 1, 24, '2024-12-14', 2, 0, 12, 0, 0, '2024-12-14 10:26:12', '2024-12-14 10:26:12'),
(36, 1, 25, 'PD10', 1, 24, '2024-12-14', 2, 0, 12, 0, 0, '2024-12-14 10:26:19', '2024-12-14 10:26:19'),
(37, 1, 25, 'PD10', 1, 24, '2024-12-14', 2, 0, 12, 0, 0, '2024-12-14 10:28:50', '2024-12-14 10:28:50'),
(38, 1, 25, 'PD10', 1, 1, '2024-12-14', 2, 0, 12, 0, 0, '2024-12-14 10:29:05', '2024-12-14 10:29:05'),
(39, 13, 26, 'SKU0026', 1, 0, '2024-12-14', 4, 0, 0, 0, 0, '2024-12-14 11:55:13', '2024-12-14 11:55:13'),
(40, 14, 22, 'SO128', 1, 0, '2024-12-14', 4, 0, 0, 0, 0, '2024-12-14 11:58:48', '2024-12-14 11:58:48'),
(41, 1, 25, 'PD10', 1, 12, '2024-12-14', 2, 0, 0, 0, 0, '2024-12-14 12:49:41', '2024-12-14 12:49:41'),
(42, 1, 25, 'PD10', 100, 24, '2024-12-14', 2, 2, 0, 0, 0, '2024-12-14 12:53:11', '2024-12-14 12:53:11'),
(43, 1, 25, 'PD10', 100, 24, '2024-12-14', 2, 2, 0, 0, 0, '2024-12-14 12:54:38', '2024-12-14 12:54:38'),
(44, 1, 25, 'PD10', 100, 0, '2024-12-14', 1, 2, 0, 0, 0, '2024-12-14 12:59:51', '2024-12-14 12:59:51'),
(45, 1, 25, 'PD10', 1, 0, '2024-12-14', 1, 1, 0, 0, 0, '2024-12-14 18:28:58', '2024-12-14 18:28:58');

-- --------------------------------------------------------

--
-- Table structure for table `bom_items`
--

CREATE TABLE `bom_items` (
  `id` int(11) NOT NULL,
  `product_type` varchar(100) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` int(11) NOT NULL,
  `bom_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bom_items`
--

INSERT INTO `bom_items` (`id`, `product_type`, `item_id`, `quantity`, `unit_price`, `bom_id`, `amount`, `created_at`, `updated_at`) VALUES
(47, 'rawmaterial', 45, 22, 21, 26, 462, '2024-12-11 10:46:47', '2024-12-11 10:46:47'),
(51, 'rawmaterial', 58, 33, 23, 26, 759, '2024-12-11 10:46:47', '2024-12-11 10:46:47'),
(52, 'rawmaterial', 43, 2, 12, 26, 24, '2024-12-11 10:46:47', '2024-12-11 10:46:47'),
(53, 'rawmaterial', 53, 4, 4, 26, 16, '2024-12-11 10:46:47', '2024-12-11 10:46:47'),
(54, 'rawmaterial', 45, 2, 100, 27, 200, '2024-12-11 10:51:26', '2024-12-11 10:51:26'),
(55, 'rawmaterial', 56, 2, 100, 27, 200, '2024-12-11 10:51:26', '2024-12-11 10:51:26'),
(56, 'rawmaterial', 50, 5, 200, 27, 1000, '2024-12-11 10:51:26', '2024-12-11 10:51:26'),
(57, 'rawmaterial', 43, 50, 22, 28, 1100, '2024-12-12 14:26:00', '2024-12-12 14:26:00'),
(58, 'rawmaterial', 43, 22, 12, 29, 264, '2024-12-12 14:27:54', '2024-12-12 14:27:54'),
(59, 'semifinished', 1, 5, 10, 30, 50, '2024-12-12 14:57:41', '2024-12-12 14:57:41'),
(60, 'rawmaterial', 53, 5, 10, 30, 50, '2024-12-12 14:57:41', '2024-12-12 14:57:41'),
(61, 'rawmaterial', 66, 100, 12, 31, 1200, '2024-12-14 12:50:48', '2024-12-14 12:50:48'),
(62, 'rawmaterial', 56, 100, 12, 31, 1200, '2024-12-14 12:50:48', '2024-12-14 12:50:48'),
(63, 'semifinished', 1, 2, 12, 31, 24, '2024-12-14 12:50:48', '2024-12-14 12:50:48'),
(64, 'semifinished', 2, 2, 12, 31, 24, '2024-12-14 12:50:48', '2024-12-14 12:50:48'),
(65, 'rawmaterial', 43, 12, 12, 32, 144, '2024-12-14 10:20:22', '2024-12-14 10:20:22'),
(66, 'rawmaterial', 43, 12, 12, 33, 144, '2024-12-14 10:23:31', '2024-12-14 10:23:31'),
(67, 'rawmaterial', 43, 2, 12, 34, 24, '2024-12-14 10:25:17', '2024-12-14 10:25:17'),
(68, 'rawmaterial', 43, 2, 12, 35, 24, '2024-12-14 10:26:12', '2024-12-14 10:26:12'),
(69, 'rawmaterial', 43, 2, 12, 36, 24, '2024-12-14 10:26:19', '2024-12-14 10:26:19'),
(70, 'rawmaterial', 43, 2, 12, 37, 24, '2024-12-14 10:28:50', '2024-12-14 10:28:50'),
(71, 'rawmaterial', 43, 2, 12, 38, 24, '2024-12-14 11:55:45', '2024-12-14 11:55:45'),
(72, 'rawmaterial', 42, 100, 10, 39, 1000, '2024-12-14 12:48:13', '2024-12-14 12:48:13'),
(73, 'rawmaterial', 45, 10, 10, 40, 100, '2024-12-14 14:54:30', '2024-12-14 14:54:30'),
(74, 'semifinished', 1, 1, 12, 41, 12, '2024-12-14 12:49:41', '2024-12-14 12:49:41'),
(75, 'rawmaterial', 43, 2, 12, 42, 24, '2024-12-14 12:53:11', '2024-12-14 12:53:11'),
(76, 'rawmaterial', 43, 2, 12, 43, 24, '2024-12-14 12:54:38', '2024-12-14 12:54:38'),
(77, 'rawmaterial', 43, 2, 12, 44, 24, '2024-12-14 18:38:54', '2024-12-14 18:38:54'),
(78, 'semifinished', 1, 2, 12, 45, 24, '2024-12-14 18:42:52', '2024-12-14 18:42:52');

-- --------------------------------------------------------

--
-- Table structure for table `bom_operation`
--

CREATE TABLE `bom_operation` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `default_workstation_id` int(11) NOT NULL,
  `is_corrective_operation` int(11) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bom_operation`
--

INSERT INTO `bom_operation` (`id`, `name`, `default_workstation_id`, `is_corrective_operation`, `description`, `created_at`, `updated_at`) VALUES
(2, 'operation_name 2', 7, 0, '', '2024-12-12 10:49:33', '2024-12-13 06:17:43'),
(3, 'operation_name 3', 6, 0, '', '2024-12-12 10:49:49', '2024-12-12 10:49:49'),
(4, 'operation_name 4', 7, 0, '', '2024-12-12 11:18:24', '2024-12-12 11:18:24'),
(5, 'operation_name', 7, 1, 'hello world', '2024-12-12 12:57:45', '2024-12-12 12:57:45');

-- --------------------------------------------------------

--
-- Table structure for table `bom_operation_list`
--

CREATE TABLE `bom_operation_list` (
  `id` int(11) NOT NULL,
  `bom_id` int(11) NOT NULL,
  `operation_id` int(11) NOT NULL,
  `per_hour_cost` int(11) NOT NULL,
  `opertaion_time` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bom_operation_list`
--

INSERT INTO `bom_operation_list` (`id`, `bom_id`, `operation_id`, `per_hour_cost`, `opertaion_time`, `amount`, `created_at`, `updated_at`) VALUES
(1, 38, 2, 1707, 1, 1707, '2024-12-14 10:29:05', '2024-12-14 11:55:45'),
(2, 38, 4, 1707, 12, 20484, '2024-12-14 11:55:45', '2024-12-14 11:55:45'),
(6, 39, 4, 1707, 12, 20484, '2024-12-14 12:47:48', '2024-12-14 12:48:13'),
(7, 41, 5, 1707, 12, 20484, '2024-12-14 12:49:41', '2024-12-14 12:49:41'),
(13, 44, 2, 1707, 10, 17070, '2024-12-14 18:38:54', '2024-12-14 18:38:54'),
(15, 45, 3, 1670, 20, 33400, '2024-12-14 18:41:41', '2024-12-14 18:42:52'),
(16, 45, 4, 1707, 10, 17070, '2024-12-14 18:41:41', '2024-12-14 18:42:52');

-- --------------------------------------------------------

--
-- Table structure for table `bom_scrap_items`
--

CREATE TABLE `bom_scrap_items` (
  `id` int(11) NOT NULL,
  `product_type` varchar(255) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` int(11) NOT NULL,
  `bom_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bom_scrap_items`
--

INSERT INTO `bom_scrap_items` (`id`, `product_type`, `item_id`, `quantity`, `unit_price`, `bom_id`, `amount`, `created_at`, `updated_at`) VALUES
(1, 'rawmaterial', 43, 5, 12, 31, 60, '2024-12-12 19:19:18', '2024-12-14 12:50:48'),
(2, 'semifinished', 2, 5, 12, 31, 60, '2024-12-12 19:19:18', '2024-12-14 12:50:48'),
(3, 'rawmaterial', 53, 3, 122, 31, 366, '2024-12-13 11:31:51', '2024-12-14 12:50:48'),
(5, 'rawmaterial', 43, 2, 12, 32, 24, '2024-12-14 10:20:22', '2024-12-14 10:20:22'),
(6, 'rawmaterial', 43, 2, 12, 33, 24, '2024-12-14 10:23:31', '2024-12-14 10:23:31'),
(7, 'rawmaterial', 56, 1, 1, 34, 1, '2024-12-14 10:25:17', '2024-12-14 10:25:17'),
(8, 'rawmaterial', 56, 1, 1, 35, 1, '2024-12-14 10:26:12', '2024-12-14 10:26:12'),
(9, 'rawmaterial', 56, 1, 1, 36, 1, '2024-12-14 10:26:19', '2024-12-14 10:26:19'),
(10, 'rawmaterial', 56, 1, 1, 37, 1, '2024-12-14 10:28:50', '2024-12-14 10:28:50'),
(11, 'rawmaterial', 56, 1, 1, 38, 1, '2024-12-14 10:29:05', '2024-12-14 11:55:45');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(140) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`, `created_at`, `created_by`) VALUES
(1, 'Audi', '1645593031', 1),
(2, 'BMW', '1645593256', 1),
(5, 'KTM', '1703420310', 1),
(8, 'MT -15', '1718428400', 1);

-- --------------------------------------------------------

--
-- Table structure for table `calender_events`
--

CREATE TABLE `calender_events` (
  `event_id` int(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `Start_data` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `end_data` datetime NOT NULL,
  `public_event` int(100) NOT NULL,
  `is_start_notified` int(100) NOT NULL,
  `reminder_before` int(100) NOT NULL,
  `reminder_before_type` varchar(100) NOT NULL,
  `event_color` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `calender_events`
--

INSERT INTO `calender_events` (`event_id`, `title`, `description`, `user_id`, `Start_data`, `end_data`, `public_event`, `is_start_notified`, `reminder_before`, `reminder_before_type`, `event_color`) VALUES
(20, 'today', 'test', 0, '2024-05-06 16:09:00', '2024-05-24 17:10:00', 1, 0, 45, 'Minutes', '#da6110');

-- --------------------------------------------------------

--
-- Table structure for table `contractors`
--

CREATE TABLE `contractors` (
  `contractor_id` int(11) NOT NULL,
  `con_code` varchar(140) NOT NULL,
  `name` varchar(140) NOT NULL,
  `contact_person` varchar(140) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_1` varchar(13) NOT NULL,
  `phone_2` varchar(13) NOT NULL,
  `gst_no` varchar(30) NOT NULL,
  `pan_no` varchar(30) NOT NULL,
  `website` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contractors`
--

INSERT INTO `contractors` (`contractor_id`, `con_code`, `name`, `contact_person`, `email`, `phone_1`, `phone_2`, `gst_no`, `pan_no`, `website`, `address`, `city`, `state`, `country`, `zipcode`, `active`, `description`, `created_at`, `created_by`) VALUES
(13, 'CON01', 'Production ', 'test', 'test@gmail.com', '01234567890', '01234567890', '8785657', '744168579663', '', 'chennai', 'Thiruvallur', 'tamilnadu', 'India', '602024', 1, 'szgseh', '1704176004', 1);

-- --------------------------------------------------------

--
-- Table structure for table `contractor_payments`
--

CREATE TABLE `contractor_payments` (
  `contractor_pay_id` int(11) NOT NULL,
  `project_wgrp_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `paid_on` date NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contractor_payments`
--

INSERT INTO `contractor_payments` (`contractor_pay_id`, `project_wgrp_id`, `payment_id`, `amount`, `paid_on`, `transaction_id`, `notes`) VALUES
(2, 7, 1, '2000.00', '2022-05-05', '', ''),
(3, 7, 1, '1000.00', '2022-05-04', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `contracttype`
--

CREATE TABLE `contracttype` (
  `cont_id` int(100) NOT NULL,
  `cont_name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contracttype`
--

INSERT INTO `contracttype` (`cont_id`, `cont_name`, `created_at`) VALUES
(57, 'test@1', '2024-05-17 14:56:42'),
(58, 'test@2', '2024-05-17 14:58:40'),
(60, 'test@4', '2024-05-17 15:03:09'),
(68, 'udhaya-ci4', '2024-05-27 17:46:05'),
(69, 'Hello', '2024-05-27 17:48:19'),
(73, 'sdsfd', '2024-05-27 17:51:34'),
(80, 'last', '2024-05-27 18:10:20'),
(81, 'Last type', '2024-05-30 17:58:29'),
(83, 'New_one', '2024-06-03 10:36:38');

-- --------------------------------------------------------

--
-- Table structure for table `contract_commend`
--

CREATE TABLE `contract_commend` (
  `discussion_id` int(100) NOT NULL,
  `dicussion_note` text NOT NULL,
  `contract_id` int(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contract_commend`
--

INSERT INTO `contract_commend` (`discussion_id`, `dicussion_note`, `contract_id`, `user_id`, `created_at`) VALUES
(60, 'hi', 12, 1, '2024-05-27 18:10:59'),
(61, 'test commend', 13, 1, '2024-05-27 18:14:05'),
(62, 'test commend 2\r\n', 13, 1, '2024-05-27 18:14:23'),
(63, 'Test comment_1_2', 15, 1, '2024-05-27 18:50:51'),
(64, 'Heloo', 16, 1, '2024-05-28 10:28:29'),
(65, 'Comment', 14, 1, '2024-05-28 10:36:13'),
(66, 'test', 14, 1, '2024-05-28 10:36:28'),
(67, 'hi', 17, 1, '2024-05-28 10:45:12'),
(68, 'njknjk', 19, 1, '2024-05-28 10:47:01'),
(69, 'z', 18, 1, '2024-05-28 10:53:11'),
(79, 'aaa', 22, 1, '2024-05-30 18:37:31'),
(80, 'sss', 22, 1, '2024-05-30 18:49:13'),
(81, 'new\r\n', 24, 1, '2024-05-31 16:00:33'),
(82, 'New1', 31, 1, '2024-06-03 10:39:41'),
(83, 'Hello', 36, 1, '2024-06-10 12:24:14'),
(84, 'test', 36, 1, '2024-06-10 12:24:19');

-- --------------------------------------------------------

--
-- Table structure for table `contract_renewals`
--

CREATE TABLE `contract_renewals` (
  `id` int(100) NOT NULL,
  `contractid` int(100) NOT NULL,
  `old_start_date` date NOT NULL DEFAULT current_timestamp(),
  `new_start_date` date NOT NULL DEFAULT current_timestamp(),
  `old_end_date` date NOT NULL DEFAULT current_timestamp(),
  `new_end_date` date NOT NULL DEFAULT current_timestamp(),
  `old_value` int(100) NOT NULL,
  `new_value` int(100) NOT NULL,
  `date_renewed` datetime NOT NULL DEFAULT current_timestamp(),
  `renewed_by` varchar(100) NOT NULL,
  `renewed_by_staff_id` int(100) NOT NULL,
  `is_on_old_expiry_notified` smallint(6) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contract_renewals`
--

INSERT INTO `contract_renewals` (`id`, `contractid`, `old_start_date`, `new_start_date`, `old_end_date`, `new_end_date`, `old_value`, `new_value`, `date_renewed`, `renewed_by`, `renewed_by_staff_id`, `is_on_old_expiry_notified`) VALUES
(12, 20, '2024-05-28', '2024-05-28', '2024-06-27', '2024-06-29', 750000, 750000, '2024-05-28 17:43:09', 'Admin', 1, 0),
(14, 32, '2024-06-03', '2024-06-03', '2024-06-29', '2024-06-29', 560000, 560000, '2024-11-20 12:08:49', 'Admin', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `contract_tasks`
--

CREATE TABLE `contract_tasks` (
  `task_id` int(11) NOT NULL,
  `contract_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `related_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `assignees` int(11) NOT NULL,
  `followers` int(11) NOT NULL,
  `task_description` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contract_tasks`
--

INSERT INTO `contract_tasks` (`task_id`, `contract_id`, `name`, `status`, `start_date`, `due_date`, `related_id`, `priority`, `assignees`, `followers`, `task_description`, `created_by`, `created_at`) VALUES
(7, 20, 'QBrainstorm Software', 2, '2024-05-30', '2024-06-06', 21, 3, 14, 14, 'helloo', 1, '2024-05-30'),
(10, 32, 'asasaS', 1, '2024-06-21', '2024-06-14', 31, 0, 14, 14, 'adaad', 1, '2024-06-07'),
(11, 32, 'adfdsaf', 2, '2024-06-07', '2024-06-08', 32, 0, 14, 14, 'adfdfdf', 1, '2024-06-07'),
(12, 32, 'adsff', 2, '2024-05-29', '2024-06-23', 31, 2, 14, 14, 'assdsd', 1, '2024-06-07'),
(13, 36, 'Test one', 0, '2024-11-19', '2024-11-23', 31, 0, 14, 15, 'this is test task', 1, '2024-11-19'),
(14, 36, 'second', 1, '2024-11-19', '2024-11-23', 32, 1, 14, 14, 'aaaaaa', 1, '2024-11-19');

-- --------------------------------------------------------

--
-- Table structure for table `costing`
--

CREATE TABLE `costing` (
  `costing_id` int(11) NOT NULL,
  `raw_material_cost` double NOT NULL,
  `operating_cost` double NOT NULL,
  `scrap_cost` double NOT NULL,
  `total_cost` double NOT NULL,
  `planning_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `credits_applied`
--

CREATE TABLE `credits_applied` (
  `id` int(11) NOT NULL,
  `credit_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `date_applied` date NOT NULL,
  `amount` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `credits_applied`
--

INSERT INTO `credits_applied` (`id`, `credit_id`, `invoice_id`, `staff_id`, `date_applied`, `amount`) VALUES
(1, 1, 3, 1, '2024-02-06', '10.00'),
(2, 1, 3, 1, '2024-02-06', '10.00'),
(3, 1, 3, 1, '2024-02-06', '4060.00'),
(4, 1, 3, 1, '2024-02-06', '100.00'),
(5, 1, 4, 1, '2024-02-06', '820.00'),
(6, 4, 19, 1, '2024-06-25', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `credit_items`
--

CREATE TABLE `credit_items` (
  `credit_item_id` int(11) NOT NULL,
  `credit_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `unit_price` decimal(14,2) NOT NULL,
  `amount` decimal(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `credit_notes`
--

CREATE TABLE `credit_notes` (
  `credit_id` int(11) NOT NULL,
  `code` varchar(140) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `applied` tinyint(1) NOT NULL DEFAULT 0,
  `issued_date` date NOT NULL,
  `other_charge` decimal(14,2) NOT NULL,
  `applied_amount` int(11) NOT NULL,
  `balance_amount` int(11) NOT NULL,
  `payment_terms` varchar(140) NOT NULL,
  `terms_condition` text NOT NULL,
  `remarks` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `credit_notes`
--

INSERT INTO `credit_notes` (`credit_id`, `code`, `cust_id`, `invoice_id`, `applied`, `issued_date`, `other_charge`, `applied_amount`, `balance_amount`, `payment_terms`, `terms_condition`, `remarks`, `created_at`, `created_by`) VALUES
(1, 'CN-001', 45, 0, 0, '2024-02-06', '5000.00', 0, 0, 'setset', '<p>test</p>', 'test', '1707223371', 1),
(2, 'CN-002', 45, 0, 0, '2024-06-11', '50000.00', 0, 0, 'expense add', '<p>asdsads</p>', 'asdsdsdsd', '1718087205', 1),
(3, 'CN-003', 45, 0, 0, '2024-06-25', '0.00', 0, 0, 'syfdsg', '', '', '1719293318', 1),
(4, 'CN-004', 37, 0, 0, '2024-06-25', '0.00', 0, 0, 'sadsad', '', '', '1719299435', 1);

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `currency_id` int(11) NOT NULL,
  `iso_code` char(3) NOT NULL,
  `symbol` varchar(20) NOT NULL,
  `decimal_sep` char(1) NOT NULL,
  `thousand_sep` char(1) NOT NULL,
  `place` varchar(10) NOT NULL,
  `is_default` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`currency_id`, `iso_code`, `symbol`, `decimal_sep`, `thousand_sep`, `place`, `is_default`, `created_at`, `created_by`) VALUES
(6, 'INR', 'र', '.', ',', 'after', 0, '1704190884', 1),
(8, 'INR', 'र', '.', ',', 'before', 0, '1705642847', 1),
(11, 'USD', '$', '.', ',', 'before', 0, '1705643125', 1),
(13, 'USD', '$', '.', ',', 'after', 0, '1708424855', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `cust_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `position` varchar(140) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `fax_num` varchar(11) NOT NULL,
  `office_num` varchar(11) NOT NULL,
  `company` varchar(255) NOT NULL,
  `gst` varchar(20) NOT NULL,
  `website` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `remarks` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`cust_id`, `name`, `position`, `address`, `city`, `state`, `country`, `zip`, `email`, `phone`, `fax_num`, `office_num`, `company`, `gst`, `website`, `description`, `remarks`, `created_at`, `created_by`) VALUES
(37, 'admin', 'test', 'test', 'test', 'test', 'test', 'test', 'test@1gmail.com', '9876543210', '', '', 'test company1', '1234', '', '', '', '1645095130', 1),
(38, 'admin2', 'test', 'No.76, First Floor', 'test', 'test', 'test', 'test', 'ashokkumar@qbrainstorm.com', '9876543211', '', '', 'test company2', '1235', '', '\r', '', '1645095130', 1),
(45, 'john', 'web developer', 'No.190, First Floor', 'Thiruvallur', 'tamilnadu', 'India', '602024', 'udhayakumar@qbrainstorm.com ', '1234567890', '1233554', '4654878554', 'qbsoftware company', '5754654466689898', '', 'sdfghdf', 'gdfshsfd', '1704452307', 1),
(46, 'jacob', 'Purchase Manager', 'No.178, First Floor', 'Thiruvallur', 'tamilnadu', 'India', '602024', 'softriders@gmail.com', '23256789', '65454546', '8765446', 'softriders Global Pvvt Limited', '535468564', '', 'sdvdf', 'dfgsd', '1704452363', 1),
(47, 'geroge', 'web developer', 'No.16, First Floor', 'Thiruvallur', 'tamilnadu', 'India', '602024', 'hrfksj@gmail.com', '8768797887', '896987687', '7984655868', 'Google', '87987897687', '', 'drtytd', 'srthrdh', '1704452430', 1),
(49, 'DEMO ', 'test', 'No.164, First Floor', 'Chennai', 'Tamil Nadu', 'India', '600087', 'Newmail@gmail.com', '9865647121', '8886876', '9876565735', 'NEWEMAIL', '7', 'https://brito.com', 'Description done', 'Description done', '1714197989', 1),
(50, 'TDWWS', 'test', 'Alvarpettai', 'Chennai', 'Tamil Nadu', 'India', '600087', 'Demo@test.com', '9564784521', '', '', 'GB BABA ', '', 'www.demo.com', 'demo ', '', '1731151111', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer_billingaddr`
--

CREATE TABLE `customer_billingaddr` (
  `billingaddr_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_billingaddr`
--

INSERT INTO `customer_billingaddr` (`billingaddr_id`, `address`, `city`, `state`, `country`, `zipcode`, `cust_id`, `created_at`, `created_by`) VALUES
(2, 'No.164, First Floor, Arcot Rd, Valasaravakkam', 'Chennai', 'Tamil Nadu', 'India', '600087', 37, '1706620472', 1),
(4, 't nagar, south street, 100 ft', 'Chennai', 'Tamil Nadu fdsf', 'India', '600089', 46, '1707904376', 1),
(6, 'ABC street', 'Chennai', 'Tamil Nadu', 'India', '600089', 47, '1707914049', 1),
(7, 'ABC street ', 'Chennai', 'Tamil Nadu', 'India', '600089', 38, '1707915673', 1),
(8, 'ABCD', 'Chennai', 'Tamil Nadu', 'India', '600089', 45, '1707916741', 1),
(9, 'No.164, Walmon,south street', 'coimbator', 'Tamil Nadu', 'India', '600060', 48, '1708432182', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer_contacts`
--

CREATE TABLE `customer_contacts` (
  `contact_id` int(11) NOT NULL,
  `firstname` varchar(140) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `position` varchar(140) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  `password` varchar(225) NOT NULL,
  `primary_contact` int(11) NOT NULL DEFAULT 0,
  `profile_image` varchar(225) NOT NULL,
  `permission` longtext NOT NULL,
  `password_updated_at` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_contacts`
--

INSERT INTO `customer_contacts` (`contact_id`, `firstname`, `lastname`, `email`, `phone`, `active`, `position`, `cust_id`, `created_at`, `created_by`, `password`, `primary_contact`, `profile_image`, `permission`, `password_updated_at`) VALUES
(1, 'Production', 'jacob', 'admin3@example.com', '9080780700', 0, 'Purchase Manager', 6, '1645002120', 1, '', 0, '', '', NULL),
(2, 'Production', 'john', 'admin2@example.com', '9080780700', 1, 'Purchase Manager', 6, '1645002166', 1, '', 0, '', '', NULL),
(3, 'Production', 'john', 'admin@example.com', '9080780700', 1, 'Purchase Manager', 6, '1645073995', 1, '', 0, '', '', NULL),
(4, 'QBS', 'Support', 'support@qbrainstorm.com', '9080780700', 1, 'werwer', 41, '1703066718', 1, '', 0, '', '', NULL),
(5, 'QBS', 'Support', 'support@qbrainstorm.com', '9080780700', 1, 'werwer', 41, '1703066783', 1, '', 0, '', '', NULL),
(6, 'Production', 'Thamizharasi', 'test@gmail.com', '1234567890', 1, 'web developer', 6, '1703827099', 1, '', 0, '', '', NULL),
(7, 'Production', 'Thamizh', 'test@gmail.com', '1234567890', 1, 'web developer', 7, '1704446817', 1, '', 0, '', '', NULL),
(10, 'QBS', 'Support', 'support@qbrainstorm.com', '9080780700', 1, 'DEV', 47, '1704958727', 1, '', 0, '', '', NULL),
(11, 'test', 'qbs', 'test1@gmail.com', '1234567890', 1, 'web dev', 45, '1706098930', 1, '', 0, '', '', NULL),
(12, 'jac', 'jacob', 'thamilarasi.v@qbrainstorm.com', '1234567890', 1, 'manager', 46, '1707470737', 1, '', 0, '', '', NULL),
(21, 'Udhaya kumar', 'R', 'udhayakumar@gmail.com', '5554567891', 1, 'customer', 37, '1732881342', 1, '$2y$10$0RehNzDBn8Je1gh.cno3SewPFs.WrnQFPADnSA6rB9lmWojIZTGJq', 1, 'sing-4.jpeg', '[\"Invoices\",\"Estimates\",\"Contracts\",\"Quotations\",\"Supports\",\"Projects\"]', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_shippingaddr`
--

CREATE TABLE `customer_shippingaddr` (
  `shippingaddr_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_shippingaddr`
--

INSERT INTO `customer_shippingaddr` (`shippingaddr_id`, `address`, `city`, `state`, `country`, `zipcode`, `cust_id`, `created_at`, `created_by`) VALUES
(3, 'ABC street', 'Chennai', 'Tamil Nadu', 'India', '600084 ', 37, '1645082553', 1),
(10, 'chennai', 'Thiruvallur', 'tamilnadu', 'India', '602024', 6, '1703827150', 1),
(11, 'Test Address', 'chennai', 'Tamil Nadu', 'India', '600087', 41, '1703828468', 1),
(12, 'chennai', 'Thiruvallur', 'tamilnadu', 'India', '602024 ', 46, '1704447100', 1),
(18, 'No.164, First Floor, Arcot Rd, Valasaravakkam', 'chennai', 'Tamil Nadu', 'India', '600089', 47, '1707914049', 1),
(19, 'No.164, First Floor, Arcot Rd, Valasaravakkam', 'chsdf', 'Tamil Nadu', 'India', '600089', 38, '1707915673', 1),
(20, 'kesavardhini', 'chennai 09', 'Tamil Nadu', 'India', '6000890', 45, '1707916741', 1),
(21, 'Ek son , west street', 'coimbator', 'Tamil Nadu', 'India', '600089', 48, '1708432220', 1);

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE `custom_fields` (
  `cf_id` int(11) NOT NULL,
  `field_type` varchar(140) NOT NULL,
  `field_related_to` varchar(255) NOT NULL,
  `field_name` varchar(140) NOT NULL,
  `field_options` varchar(255) NOT NULL,
  `required` tinyint(4) NOT NULL,
  `order_num` int(11) NOT NULL,
  `can_be_purged` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custom_fields`
--

INSERT INTO `custom_fields` (`cf_id`, `field_type`, `field_related_to`, `field_name`, `field_options`, `required`, `order_num`, `can_be_purged`) VALUES
(1, 'radio', 'customer', 'Gender', 'Male,Female,Transgender', 1, 2, 0),
(2, 'date', 'proposal', 'Payment Date', '', 1, 3, 1),
(4, 'checkbox', 'customer', 'Type', 'Grade1,Grade2,Grade3', 0, 3, 0),
(5, 'input', 'raw_material', 'Common Name', '', 1, 0, 0),
(6, 'input', 'supplier', 'Concern Name', '', 1, 1, 0),
(7, 'date', 'rfq', 'Created At', '', 1, 1, 0),
(8, 'radio', 'inventory_service', 'Provider', 'Inside,Outside', 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `custom_field_values`
--

CREATE TABLE `custom_field_values` (
  `cfv_id` int(11) NOT NULL,
  `cf_id` int(11) NOT NULL,
  `related_id` int(11) NOT NULL,
  `field_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custom_field_values`
--

INSERT INTO `custom_field_values` (`cfv_id`, `cf_id`, `related_id`, `field_value`) VALUES
(6, 1, 9, 'FeMale'),
(7, 1, 10, 'Female'),
(10, 1, 12, 'Male'),
(57, 1, 38, 'Male'),
(58, 4, 38, 'Grade1,Grade2'),
(63, 5, 7, 'Test'),
(70, 5, 11, 'Test'),
(73, 5, 13, 'Common'),
(74, 5, 14, 'Common'),
(75, 5, 15, 'Common'),
(86, 1, 31, 'FeMale'),
(87, 1, 32, 'emale'),
(88, 6, 3, 'dcghfyj'),
(89, 6, 4, 'awerhw'),
(91, 6, 6, 'wrewr'),
(92, 6, 7, 'Import'),
(93, 7, 1, '2024-01-09'),
(94, 7, 2, '2024-01-26'),
(95, 8, 1, 'Outside'),
(96, 8, 2, 'Inside'),
(97, 6, 18, 'werwer'),
(98, 6, 19, 'werwer'),
(99, 6, 20, 'Test'),
(100, 6, 21, 'Test 1'),
(101, 6, 24, '234432'),
(102, 6, 25, '234432'),
(103, 6, 26, '234432'),
(104, 6, 27, 'sdfsdf'),
(105, 6, 28, '345345'),
(106, 6, 29, '345345'),
(107, 6, 30, '345345'),
(108, 6, 31, 'Kumar Company'),
(109, 6, 32, '45645'),
(115, 6, 39, '45645'),
(116, 6, 40, '45645'),
(117, 6, 41, '45645'),
(118, 6, 42, '45645'),
(119, 6, 43, '45645'),
(120, 6, 44, '45645'),
(121, 6, 45, '45645'),
(124, 5, 18, 'Test'),
(125, 5, 16, 'Test'),
(126, 5, 25, 'SivaKumar'),
(127, 5, 26, 'rwet'),
(128, 5, 27, 'rwet'),
(129, 5, 28, 'rwet'),
(130, 5, 29, 'rwet'),
(131, 5, 30, 'rwet'),
(132, 5, 31, 'rwet'),
(133, 5, 32, 'rwet'),
(134, 5, 33, 'rwet'),
(135, 5, 34, 'rwet'),
(136, 5, 35, 'rwet'),
(137, 5, 36, 'rwet'),
(138, 5, 37, 'rwet'),
(139, 5, 38, 'rwet'),
(140, 5, 39, 'rwet'),
(141, 5, 40, 'rwet'),
(142, 5, 41, 'rwet'),
(146, 8, 4, 'Outside'),
(147, 5, 2, 'Test'),
(148, 5, 3, 'Test'),
(149, 5, 17, 'Test'),
(150, 5, 20, 'Common'),
(151, 5, 21, 'Common'),
(152, 5, 24, 'SevenSanjay'),
(153, 5, 42, '6456'),
(154, 5, 43, 'SivaKumar'),
(155, 1, 41, 'Male'),
(156, 1, 43, 'Male'),
(157, 4, 43, 'Grade2'),
(158, 1, 44, 'Male'),
(159, 4, 44, 'Grade2'),
(162, 1, 11, 'Female'),
(163, 5, 44, 'dfggdsfgf'),
(165, 8, 5, 'Inside'),
(166, 8, 6, 'Outside'),
(167, 6, 46, '234324'),
(168, 7, 5, '2023-12-26'),
(169, 7, 6, '2023-12-27'),
(170, 1, 6, 'Male'),
(171, 4, 6, 'Grade1'),
(173, 5, 45, 'dfggdsfgf'),
(175, 5, 47, 'setj'),
(176, 5, 46, 'aez6t'),
(178, 5, 49, 'ser'),
(181, 5, 48, 'er7yk'),
(182, 8, 7, 'Inside'),
(183, 6, 47, 'test concern'),
(184, 6, 48, 'jh'),
(185, 5, 50, 'ser'),
(187, 5, 51, 'ser'),
(188, 5, 52, 'awrweth'),
(189, 7, 7, '2023-12-29'),
(191, 5, 53, 'sdfdsf'),
(192, 7, 8, '2023-12-29'),
(193, 7, 9, '2023-12-30'),
(194, 6, 49, 'ERST5UJR5'),
(195, 7, 10, '2023-12-30'),
(196, 6, 50, 'srycfjrs'),
(197, 7, 11, '2023-12-30'),
(198, 7, 12, '2023-12-30'),
(199, 7, 13, '2023-12-30'),
(200, 6, 51, 'esthedthe'),
(201, 6, 52, 'bghkvfg'),
(202, 6, 1, 'awerhw'),
(203, 6, 2, 'dertu'),
(204, 8, 8, 'Inside'),
(205, 8, 9, 'Outside'),
(206, 8, 10, 'Inside'),
(207, 5, 54, 'xdfg'),
(208, 5, 55, 'adrgsh'),
(210, 5, 56, 'awzrhgesh'),
(211, 5, 57, 'sertyhrth'),
(212, 7, 14, '2024-01-02'),
(214, 5, 58, 'sdgr'),
(216, 5, 59, 'dxfzh'),
(220, 5, 61, 'aefgae'),
(222, 5, 62, 'dxhfxg'),
(224, 5, 63, 'Seven Sanjay'),
(225, 5, 64, 'asdfsdf'),
(226, 5, 65, 'sadd'),
(227, 5, 60, 'eharxr'),
(228, 7, 15, '2024-01-03'),
(235, 7, 16, '2024-01-09'),
(237, 5, 66, 'awerg'),
(238, 5, 67, 'aehrst'),
(239, 8, 11, 'Inside'),
(240, 1, 46, 'Male'),
(241, 4, 46, 'Grade2'),
(242, 1, 47, 'Male'),
(243, 4, 47, 'Grade2'),
(247, 5, 69, 'sdagsag'),
(249, 5, 70, 'fsdgsdfh'),
(250, 8, 12, 'Inside'),
(251, 5, 68, 'asdgasg'),
(252, 6, 5, 'as'),
(253, 1, 37, 'Male'),
(254, 4, 37, 'Grade1,Grade2'),
(255, 1, 45, 'Male'),
(256, 4, 45, 'Grade2'),
(257, 1, 48, 'Female'),
(258, 4, 48, 'Grade2'),
(259, 1, 49, 'Male'),
(260, 4, 49, 'Grade1'),
(261, 7, 3, '2024-05-02'),
(262, 1, 50, 'Female'),
(263, 4, 50, 'Grade1');

-- --------------------------------------------------------

--
-- Table structure for table `deductions`
--

CREATE TABLE `deductions` (
  `deduct_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `description` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `value` decimal(14,2) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deductions`
--

INSERT INTO `deductions` (`deduct_id`, `name`, `description`, `type`, `value`, `created_at`, `created_by`) VALUES
(1, 'T Tax', 'hello2', 0, '5.00', '1651037240', 1),
(2, 'X Tax', '', 1, '1000.00', '1651037261', 1),
(3, 'QBS ', 'dwsd', 0, '343.00', '1703163194', 1);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_records`
--

CREATE TABLE `delivery_records` (
  `delivery_id` int(11) NOT NULL,
  `transport_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `type` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_records`
--

INSERT INTO `delivery_records` (`delivery_id`, `transport_id`, `related_to`, `related_id`, `status`, `type`) VALUES
(2, 2, 'purchase_order', 2, 2, 0),
(3, 1, 'purchase_order', 1, 2, 0),
(4, 6, 'purchase_order', 3, 2, 0),
(8, 7, 'purchase_order', 26, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `name`, `description`, `created_at`, `created_by`) VALUES
(1, 'Testing', 'Testing Product', '1648194570', 1),
(3, 'Developing', 'Developing product\r\n', '1648194606', 1),
(4, 'Marketing', 'No Department of', '1648197404', 1),
(5, 'Civil', 'Services Department', '1703144707', 1),
(11, 'asas', 'aaa', '1715064476', 1);

-- --------------------------------------------------------

--
-- Table structure for table `designation`
--

CREATE TABLE `designation` (
  `designation_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `designation`
--

INSERT INTO `designation` (`designation_id`, `department_id`, `name`, `description`, `created_at`, `created_by`) VALUES
(1, 4, 'Executive', 'chief executive position 2', '1648199872', 1),
(2, 3, 'Manager', 'Jobs', '1648200065', 1),
(3, 3, 'Domain', '', '1703148468', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dispatch`
--

CREATE TABLE `dispatch` (
  `dispatch_id` int(100) NOT NULL,
  `order_code` varchar(255) NOT NULL,
  `warehouse` varchar(255) NOT NULL,
  `delivery_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `customer` varchar(255) NOT NULL,
  `suppiler_id` int(100) NOT NULL,
  `order_id` int(255) NOT NULL,
  `cust_id` int(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `pick_list_id` int(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `updated_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dispatch`
--

INSERT INTO `dispatch` (`dispatch_id`, `order_code`, `warehouse`, `delivery_date`, `customer`, `suppiler_id`, `order_id`, `cust_id`, `status`, `pick_list_id`, `description`, `created_at`, `updated_at`) VALUES
(16, 'ghdrfg5868796', '', '2024-01-13 12:15:00', 'geroge', 0, 31, 47, 1, 0, 'asd', '1705140797', '1705140797'),
(19, 'ghdrfg5868796', '', '0000-00-00 00:00:00', 'geroge', 0, 31, 47, 3, 0, 'xgsagr', '1705146617', '1705146617'),
(20, 'ghdrfg5868796', '', '0000-00-00 00:00:00', 'geroge', 0, 31, 47, 0, 0, '', '1705148902', '1705148902'),
(21, 'ghdrfg5868796', '', '2024-01-24 15:15:00', 'geroge', 0, 31, 47, 1, 0, 'zdfhafh', '1705749070', '1705749070'),
(23, 'USAP234', '', '2024-01-22 11:11:00', 'geroge', 0, 36, 47, 2, 0, 'gdfgdf', '1705907263', '1705907263');

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `template_name` varchar(50) NOT NULL,
  `email_subject` text NOT NULL,
  `default_message` mediumtext NOT NULL,
  `custom_message` mediumtext DEFAULT NULL,
  `template_type` enum('default','custom') NOT NULL DEFAULT 'default',
  `language` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `template_name`, `email_subject`, `default_message`, `custom_message`, `template_type`, `language`, `deleted`) VALUES
(1, 'login_info', 'Login details', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"><div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\">  <h1>Login Details</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\"> Hello {USER_FIRST_NAME} {USER_LAST_NAME},<br><br>An account has been created for you.</p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\"> Please use the following info to login your dashboard:</p>            <hr>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">Dashboard URL:&nbsp;<a href=\"{DASHBOARD_URL}\" target=\"_blank\">{DASHBOARD_URL}</a></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\"></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Email: {USER_LOGIN_EMAIL}</span><br></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Password:&nbsp;{USER_LOGIN_PASSWORD}</span></p>            <p style=\"color: rgb(85, 85, 85);\"><br></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"><div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\">  <h1>Login Details</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\"> Hello {USER_FIRST_NAME} {USER_LAST_NAME},<br><br>An account has been created for you.</p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\"> Please use the following info to login your dashboard:</p>            <hr>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">Dashboard URL:&nbsp;<a href=\"{DASHBOARD_URL}\" target=\"_blank\">{DASHBOARD_URL}</a></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\"></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Email: {USER_LOGIN_EMAIL}</span><br></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Password:&nbsp;{USER_LOGIN_PASSWORD}</span></p>            <p style=\"color: rgb(85, 85, 85);\"><br></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', 'default', '', 0),
(2, 'reset_password', 'Reset password', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"><div style=\"max-width:640px; margin:0 auto; \"><div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Reset Password</h1>\n </div>\n <div style=\"padding: 20px; background-color: rgb(255, 255, 255); color:#555;\">                    <p style=\"font-size: 14px;\"> Hello {ACCOUNT_HOLDER_NAME},<br><br>A password reset request has been created for your account.&nbsp;</p>\n                    <p style=\"font-size: 14px;\"> To initiate the password reset process, please click on the following link:</p>\n                    <p style=\"font-size: 14px;\"><a href=\"{RESET_PASSWORD_URL}\" target=\"_blank\">Reset Password</a></p>\n                    <p style=\"font-size: 14px;\"></p>\n                    <p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\"><br></span></p>\n<p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\">If you\'ve received this mail in error, it\'s likely that another user entered your email address by mistake while trying to reset a password.</span><br></p>\n<p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\">If you didn\'t initiate the request, you don\'t need to take any further action and can safely disregard this email.</span><br></p>\n<p style=\"font-size: 14px;\"><br></p>\n<p style=\"font-size: 14px;\">{SIGNATURE}</p>\n                </div>\n            </div>\n        </div>', '', 'default', '', 0),
(3, 'team_member_invitation', 'You are invited', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"><div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Account Invitation</h1>   </div>  <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello,</span><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"font-weight: bold;\"><br></span></span></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"font-weight: bold;\">{INVITATION_SENT_BY}</span> has sent you an invitation to join with a team.</span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{INVITATION_URL}\" target=\"_blank\">Accept this Invitation</a></span></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">If you don not want to accept this invitation, simply ignore this email.</span><br><br></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', '', 'default', '', 0),
(4, 'send_invoice', 'New invoice', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>INVOICE #{INVOICE_ID}</h1></div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">  <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello {CONTACT_FIRST_NAME},</span><br></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\">Thank you for your business cooperation.</span><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Your invoice for the project {PROJECT_TITLE} has been generated and is attached here.</span></p><p style=\"\"><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{INVOICE_URL}\" target=\"_blank\">Show Invoice</a></span></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\">Invoice balance due is {BALANCE_DUE}</span><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Please pay this invoice within {DUE_DATE}.&nbsp;</span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>  </div> </div></div>', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>INVOICE #{INVOICE_ID}</h1></div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">  <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello {CONTACT_FIRST_NAME},</span><br></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\">Thank you for your business cooperation.</span><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Your invoice for the project {PROJECT_TITLE} has been generated and is attached here.</span></p><p style=\"\"><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{INVOICE_URL}\" target=\"_blank\">Show Invoice</a></span></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\">Invoice balance due is {BALANCE_DUE}</span><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Please pay this invoice within {DUE_DATE}.&nbsp;</span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>  </div> </div></div>', 'default', '', 0),
(5, 'signature', 'Signature', 'Powered By: <a href=\"http://qbrainstorm.com/\" target=\"_blank\">Q BRAINSTORM SOFTWARE </a>', '', 'default', '', 0),
(6, 'client_contact_invitation', 'You are invited', '<div style=\"background-color: #eeeeef; padding: 50px 0; \">    <div style=\"max-width:640px; margin:0 auto; \">  <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Account Invitation</h1> </div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello,</span><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"font-weight: bold;\"><br></span></span></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"font-weight: bold;\">{INVITATION_SENT_BY}</span> has sent you an invitation to a client portal.</span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{INVITATION_URL}\" target=\"_blank\">Accept this Invitation</a></span></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">If you don not want to accept this invitation, simply ignore this email.</span><br><br></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', '', 'default', '', 0),
(7, 'ticket_created', 'Ticket  #{TICKET_ID} - {TICKET_TITLE}', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Ticket #{TICKET_ID} Opened</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\"><p style=\"\"><span style=\"line-height: 18.5714px; font-weight: bold;\">Title: {TICKET_TITLE}</span><span style=\"line-height: 18.5714px;\"><br></span></p><p style=\"\"><span style=\"line-height: 18.5714px;\">{TICKET_CONTENT}</span><br></p> <p style=\"\"><br></p> <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{TICKET_URL}\" target=\"_blank\">Show Ticket</a></span></p> <p style=\"\"><br></p><p style=\"\">Regards,</p><p style=\"\"><span style=\"line-height: 18.5714px;\">{USER_NAME}</span><br></p>   </div>  </div> </div>', '', 'default', '', 0),
(8, 'ticket_commented', 'Ticket  #{TICKET_ID} - {TICKET_TITLE}', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Ticket #{TICKET_ID} Replies</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\"><p style=\"\"><span style=\"line-height: 18.5714px; font-weight: bold;\">Title: {TICKET_TITLE}</span><span style=\"line-height: 18.5714px;\"><br></span></p><p style=\"\"><span style=\"line-height: 18.5714px;\">{TICKET_CONTENT}</span></p><p style=\"\"><span style=\"line-height: 18.5714px;\"><br></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{TICKET_URL}\" target=\"_blank\">Show Ticket</a></span></p></div></div></div>', '', 'default', '', 0),
(9, 'ticket_closed', 'Ticket  #{TICKET_ID} - {TICKET_TITLE}', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Ticket #{TICKET_ID}</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\"><p style=\"\"><span style=\"line-height: 18.5714px;\">The Ticket #{TICKET_ID} has been closed by&nbsp;</span><span style=\"line-height: 18.5714px;\">{USER_NAME}</span></p> <p style=\"\"><br></p> <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{TICKET_URL}\" target=\"_blank\">Show Ticket</a></span></p>   </div>  </div> </div>', '', 'default', '', 0),
(10, 'ticket_reopened', 'Ticket  #{TICKET_ID} - {TICKET_TITLE}', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Ticket #{TICKET_ID}</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\"><p style=\"\"><span style=\"line-height: 18.5714px;\">The Ticket #{TICKET_ID} has been reopened by&nbsp;</span><span style=\"line-height: 18.5714px;\">{USER_NAME}</span></p><p style=\"\"><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{TICKET_URL}\" target=\"_blank\">Show Ticket</a></span></p>  </div> </div></div>', '', 'default', '', 0),
(11, 'general_notification', '{EVENT_TITLE}', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>{APP_TITLE}</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\"><p style=\"\"><span style=\"line-height: 18.5714px;\">{EVENT_TITLE}</span></p><p style=\"\"><span style=\"line-height: 18.5714px;\">{EVENT_DETAILS}</span></p><p style=\"\"><span style=\"line-height: 18.5714px;\"><br></span></p><p style=\"\"><span style=\"line-height: 18.5714px;\"></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{NOTIFICATION_URL}\" target=\"_blank\">View Details</a></span></p>  </div> </div></div>', '', 'default', '', 0),
(12, 'invoice_payment_confirmation', 'Payment received', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\">\r\n <tbody><tr>\r\n <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n <tbody><tr>\r\n <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\">\r\n                                        <tbody><tr>\r\n                                                <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n                                                        <tbody>\r\n                                                            <tr>\r\n                                                                <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n                                                                    <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\">\r\n                                                                        <tbody><tr>\r\n                                                                                <td valign=\"top\" style=\"padding-top: 40px;padding-right: 18px;padding-bottom: 40px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\">\r\n                                                                                    <h2 style=\"display: block;margin: 0;padding: 0;font-family: Arial;font-size: 30px;font-style: normal;font-weight: bold;line-height: 100%;letter-spacing: -1px;text-align: center;color: #ffffff !important;\">Payment Confirmation</h2>\r\n                                                                                </td>\r\n                                                                            </tr>\r\n                                                                        </tbody>\r\n                                                                    </table>\r\n                                                                </td>\r\n                                                            </tr>\r\n                                                        </tbody>\r\n                                                    </table>\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n                                                        <tbody>\r\n                                                            <tr>\r\n                                                                <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n\r\n                                                                    <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\">\r\n                                                                        <tbody><tr>\r\n                                                                                <td valign=\"top\" style=\"padding-top: 20px;padding-right: 18px;padding-bottom: 0;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\">\r\n                                                                                    Hello,<br>\r\n                                                                                    We have received your payment of {PAYMENT_AMOUNT} for {INVOICE_ID} <br>\r\n                                                                                    Thank you for your business cooperation.\r\n                                                                                </td>\r\n                                                                            </tr>\r\n                                                                            <tr>\r\n                                                                                <td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\">\r\n                                                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n                                                                                        <tbody>\r\n                                                                                            <tr>\r\n                                                                                                <td style=\"padding-top: 15px;padding-right: 0x;padding-bottom: 15px;padding-left: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n                                                                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n                                                                                                        <tbody>\r\n                                                                                                            <tr>\r\n                                                                                                                <td align=\"center\" valign=\"middle\" style=\"font-family: Arial;font-size: 16px;padding: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n                                                                                                                    <a href=\"{INVOICE_URL}\" target=\"_blank\" style=\"font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;display: block;\">View Invoice</a>\r\n                                                                                                                </td>\r\n                                                                                                            </tr>\r\n                                                                                                        </tbody>\r\n                                                                                                    </table>\r\n                                                                                                </td>\r\n                                                                                            </tr>\r\n                                                                                        </tbody>\r\n                                                                                    </table>\r\n                                                                                </td>\r\n                                                                            </tr>\r\n                                                                            <tr>\r\n                                                                                <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> \r\n                                                                                    \r\n                                                                                </td>\r\n                                                                            </tr>\r\n                                                                            <tr>\r\n                                                                                <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> \r\n  {SIGNATURE}\r\n  </td>\r\n </tr>\r\n </tbody>\r\n  </table>\r\n  </td>\r\n  </tr>\r\n  </tbody>\r\n </table>\r\n  </td>\r\n </tr>\r\n  </tbody>\r\n  </table>\r\n  </td>\r\n </tr>\r\n </tbody>\r\n </table>\r\n </td>\r\n </tr>\r\n </tbody>\r\n  </table>', '', 'default', '', 0),
(13, 'message_received', '{SUBJECT}', '<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\"> <meta content=\"width=device-width, initial-scale=1.0\" name=\"viewport\"> <style type=\"text/css\"> #message-container p {margin: 10px 0;} #message-container h1, #message-container h2, #message-container h3, #message-container h4, #message-container h5, #message-container h6 { padding:10px; margin:0; } #message-container table td {border-collapse: collapse;} #message-container table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; } #message-container a span{padding:10px 15px !important;} </style> <table id=\"message-container\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background:#eee; margin:0; padding:0; width:100% !important; line-height: 100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; font-family:Helvetica,Arial,sans-serif; color: #555;\"> <tbody> <tr> <td valign=\"top\"> <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"> <tbody> <tr> <td height=\"50\" width=\"600\">&nbsp;</td> </tr> <tr> <td style=\"background-color:#33333e; padding:25px 15px 30px 15px; font-weight:bold; \" width=\"600\"><h2 style=\"color:#fff; text-align:center;\">{USER_NAME} sent you a message</h2></td> </tr> <tr> <td bgcolor=\"whitesmoke\" style=\"background:#fff; font-family:Helvetica,Arial,sans-serif\" valign=\"top\" width=\"600\"> <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"> <tbody> <tr> <td height=\"10\" width=\"560\">&nbsp;</td> </tr> <tr> <td width=\"560\"><p><span style=\"background-color: transparent;\">{MESSAGE_CONTENT}</span></p> <p style=\"display:inline-block; padding: 10px 15px; background-color: #00b393;\"><a href=\"{MESSAGE_URL}\" style=\"text-decoration: none; color:#fff;\" target=\"_blank\">Reply Message</a></p> </td> </tr> <tr> <td height=\"10\" width=\"560\">&nbsp;</td> </tr> </tbody> </table> </td> </tr> <tr> <td height=\"60\" width=\"600\">&nbsp;</td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 'default', '', 0),
(14, 'invoice_due_reminder_before_due_date', 'Invoice due reminder', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 40px;padding-right: 18px;padding-bottom: 40px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> <h2 style=\"display: block;margin: 0;padding: 0;font-family: Arial;font-size: 30px;font-style: normal;font-weight: bold;line-height: 100%;letter-spacing: -1px;text-align: center;color: #ffffff !important;\">Invoice Due Reminder</h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 20px;padding-right: 18px;padding-bottom: 0;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p> Hello,<br>We would like to remind you that invoice {INVOICE_ID} is due on {DUE_DATE}. Please pay the invoice within due date.&nbsp;</p><p></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p>If you have already submitted the payment, please ignore this email.</p><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px;padding-right: 0x;padding-bottom: 15px;padding-left: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-family: Arial;font-size: 16px;padding: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><a href=\"{INVOICE_URL}\" target=\"_blank\" style=\"font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;display: block;\">View Invoice</a> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> <p></p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 40px;padding-right: 18px;padding-bottom: 40px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> <h2 style=\"display: block;margin: 0;padding: 0;font-family: Arial;font-size: 30px;font-style: normal;font-weight: bold;line-height: 100%;letter-spacing: -1px;text-align: center;color: #ffffff !important;\">Invoice Due Reminder</h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 20px;padding-right: 18px;padding-bottom: 0;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p> Hello,<br>We would like to remind you that invoice {INVOICE_ID} is due on {DUE_DATE}. Please pay the invoice within due date.&nbsp;</p><p></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p>If you have already submitted the payment, please ignore this email.</p><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px;padding-right: 0x;padding-bottom: 15px;padding-left: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-family: Arial;font-size: 16px;padding: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><a href=\"{INVOICE_URL}\" target=\"_blank\" style=\"font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;display: block;\">View Invoice</a> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> <p></p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', 'default', '', 0),
(15, 'invoice_overdue_reminder', 'Invoice overdue reminder', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 40px;padding-right: 18px;padding-bottom: 40px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> <h2 style=\"display: block;margin: 0;padding: 0;font-family: Arial;font-size: 30px;font-style: normal;font-weight: bold;line-height: 100%;letter-spacing: -1px;text-align: center;color: #ffffff !important;\">Invoice Overdue Reminder</h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 20px;padding-right: 18px;padding-bottom: 0;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p> Hello,<br>We would like to remind you that you have an unpaid invoice {INVOICE_ID}. We kindly request you to pay the invoice as soon as possible.&nbsp;</p><p></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p>If you have already submitted the payment, please ignore this email.</p><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px;padding-right: 0x;padding-bottom: 15px;padding-left: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-family: Arial;font-size: 16px;padding: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><a href=\"{INVOICE_URL}\" target=\"_blank\" style=\"font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;display: block;\">View Invoice</a> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> <p></p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 'default', '', 0);
INSERT INTO `email_templates` (`id`, `template_name`, `email_subject`, `default_message`, `custom_message`, `template_type`, `language`, `deleted`) VALUES
(16, 'recurring_invoice_creation_reminder', 'Recurring invoice creation reminder', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 40px;padding-right: 18px;padding-bottom: 40px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> <h2 style=\"display: block;margin: 0;padding: 0;font-family: Arial;font-size: 30px;font-style: normal;font-weight: bold;line-height: 100%;letter-spacing: -1px;text-align: center;color: #ffffff !important;\">Invoice Cration Reminder</h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 20px;padding-right: 18px;padding-bottom: 0;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p> Hello,<br>We would like to remind you that a recurring invoice will be created on {NEXT_RECURRING_DATE}.</p><p></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%; text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-size: 16px; padding: 10px; text-size-adjust: 100%;\"><a href=\"{INVOICE_URL}\" target=\"_blank\" style=\"font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;\">View Invoice</a></td></tr></tbody></table></td></tr></tbody></table> <p></p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 'default', '', 0),
(17, 'project_task_deadline_reminder', 'Project task deadline reminder', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>{APP_TITLE}</h1></div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">  <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello,</span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">This is to remind you that there are some tasks which deadline is {DEADLINE}.</span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">{TASKS_LIST}</span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>  </div> </div></div>', '', 'default', '', 0),
(18, 'estimate_sent', 'New estimate', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 40px 18px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"> <h2 style=\"display: block; margin: 0px; padding: 0px; line-height: 100%; text-align: center;\"><font color=\"#ffffff\" face=\"Arial\"><span style=\"letter-spacing: -1px;\"><b>ESTIMATE #{ESTIMATE_ID}</b></span></font><br></h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 20px;padding-right: 18px;padding-bottom: 0;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p> Hello {CONTACT_FIRST_NAME},<br></p><p>Here is the estimate. Please check the attachment.</p><p></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%; text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-size: 16px; padding: 10px; text-size-adjust: 100%;\"><a href=\"{ESTIMATE_URL}\" target=\"_blank\" style=\"font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;\">Show Estimate</a></td></tr></tbody></table></td></tr></tbody></table> <p></p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 'default', '', 0),
(19, 'estimate_request_received', 'Estimate request received', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 40px 18px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"> <h2 style=\"display: block; margin: 0px; padding: 0px; line-height: 100%; text-align: center;\"><font color=\"#ffffff\" face=\"Arial\"><span style=\"letter-spacing: -1px;\"><b>ESTIMATE REQUEST #{ESTIMATE_REQUEST_ID}</b></span></font><br></h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 20px 18px 0px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"><p style=\"color: rgb(96, 96, 96); font-family: Arial; font-size: 15px;\"><span style=\"background-color: transparent;\">A new estimate request has been received from {CONTACT_FIRST_NAME}.</span><br></p><p style=\"color: rgb(96, 96, 96); font-family: Arial; font-size: 15px;\"></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%; text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-size: 16px; padding: 10px; text-size-adjust: 100%;\"><a href=\"{ESTIMATE_REQUEST_URL}\" target=\"_blank\" style=\"font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;\">Show Estimate Request</a></td></tr></tbody></table></td></tr></tbody></table> <p></p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 'default', '', 0),
(20, 'estimate_rejected', 'Estimate rejected', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 40px 18px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"> <h2 style=\"display: block; margin: 0px; padding: 0px; line-height: 100%; text-align: center;\"><font color=\"#ffffff\" face=\"Arial\"><span style=\"letter-spacing: -1px;\"><b>ESTIMATE #{ESTIMATE_ID}</b></span></font><br></h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 20px 18px 0px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"><p style=\"\"><font color=\"#606060\" face=\"Arial\"><span style=\"font-size: 15px;\">The estimate #{ESTIMATE_ID} has been rejected.</span></font><br></p><p style=\"color: rgb(96, 96, 96); font-family: Arial; font-size: 15px;\"></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%; text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-size: 16px; padding: 10px; text-size-adjust: 100%;\"><a href=\"{ESTIMATE_URL}\" target=\"_blank\" style=\"font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;\">Show Estimate</a></td></tr></tbody></table></td></tr></tbody></table> <p></p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 'default', '', 0),
(21, 'estimate_accepted', 'Estimate accepted {nottu}', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 40px 18px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"> <h2 style=\"display: block; margin: 0px; padding: 0px; line-height: 100%; text-align: center;\"><font color=\"#ffffff\" face=\"Arial\"><span style=\"letter-spacing: -1px;\"><b>ESTIMATE #{ESTIMATE_ID}</b></span></font><br></h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 20px 18px 0px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"><p style=\"\"><font color=\"#606060\" face=\"Arial\"><span style=\"font-size: 15px;\">The estimate #{ESTIMATE_ID} has been accepted.</span></font><br></p><p style=\"color: rgb(96, 96, 96); font-family: Arial; font-size: 15px;\"></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%; text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-size: 16px; padding: 10px; text-size-adjust: 100%;\"><a href=\"{ESTIMATE_URL}\" target=\"_blank\" style=\"font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;\">Show Estimate</a></td></tr></tbody></table></td></tr></tbody></table> <p></p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 40px 18px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"> <h2 style=\"display: block; margin: 0px; padding: 0px; line-height: 100%; text-align: center;\"><font color=\"#ffffff\" face=\"Arial\"><span style=\"letter-spacing: -1px;\"><b>ESTIMATE #{ESTIMATE_ID}</b></span></font><br></h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 20px 18px 0px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"><p style=\"\"><font color=\"#606060\" face=\"Arial\"><span style=\"font-size: 15px;\">The estimate #{ESTIMATE_ID} has been accepted.</span></font><br></p><p style=\"color: rgb(96, 96, 96); font-family: Arial; font-size: 15px;\"></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%; text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-size: 16px; padding: 10px; text-size-adjust: 100%;\"><a href=\"{ESTIMATE_URL}\" target=\"_blank\" style=\"font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;\">Show Estimate</a></td></tr></tbody></table></td></tr></tbody></table> <p></p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', 'default', '', 0),
(22, 'new_client_greetings', 'Welcome!', '<div style=\"background-color: #eeeeef; padding: 50px 0; \">    <div style=\"max-width:640px; margin:0 auto; \">  <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Welcome to {COMPANY_NAME}</h1> </div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello {CONTACT_FIRST_NAME},</span></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Thank you for creating your account. </span></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">We are happy to see you here.<br></span></p><hr><p style=\"color: rgb(85, 85, 85); font-size: 14px;\">Dashboard URL:&nbsp;<a href=\"{DASHBOARD_URL}\" target=\"_blank\">{DASHBOARD_URL}</a></p><p style=\"color: rgb(85, 85, 85); font-size: 14px;\"></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Email: {CONTACT_LOGIN_EMAIL}</span><br></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Password:&nbsp;{CONTACT_LOGIN_PASSWORD}</span></p><p style=\"color: rgb(85, 85, 85);\"><br></p><p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', '', 'default', '', 0),
(23, 'verify_email', 'Please verify your email', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"><div style=\"max-width:640px; margin:0 auto; \"><div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Account verification</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255); color:#555;\"><p style=\"font-size: 14px;\">To initiate the signup process, please click on the following link:<br></p><p style=\"font-size: 14px;\"><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{VERIFY_EMAIL_URL}\" target=\"_blank\">Verify Email</a></span></p>  <p style=\"font-size: 14px;\"><br></p><p style=\"\"><span style=\"font-size: 14px;\">If you did not initiate the request, you do not need to take any further action and can safely disregard this email.</span></p><p style=\"\"><span style=\"font-size: 14px;\"><br></span></p><p style=\"font-size: 14px;\">{SIGNATURE}</p></div></div></div>', '', 'default', '', 0),
(24, 'new_order_received', 'New order received', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>ORDER #{ORDER_ID}</h1></div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">  <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">A new order has been received from&nbsp;</span><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">{CONTACT_FIRST_NAME} and is attached here.</span><br></p><p style=\"\"><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{ORDER_URL}\" target=\"_blank\">Show Order</a></span></p><p style=\"\"><br></p>  </div> </div></div>', '', 'default', '', 0),
(25, 'order_status_updated', 'Order status updated', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>ORDER #{ORDER_ID}</h1></div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">  <p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello {CONTACT_FIRST_NAME},</span><br></p><p><span style=\"font-size: 14px; line-height: 20px;\">Thank you for your business cooperation.</span><br></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Your order&nbsp;</span><font color=\"#555555\"><span style=\"font-size: 14px;\">has been updated&nbsp;</span></font><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">and is attached here.</span></p><p style=\"\"><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{ORDER_URL}\" target=\"_blank\">Show Order</a></span></p><p style=\"\"><br></p>  </div> </div></div>', '', 'default', '', 0),
(26, 'proposal_sent', 'Proposal sent', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 40px 18px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"> <h2 style=\"display: block; margin: 0px; padding: 0px; line-height: 100%; text-align: center;\"><font color=\"#ffffff\" face=\"Arial\"><span style=\"letter-spacing: -1px;\"><b>PROPOSAL #{PROPOSAL_ID}</b></span></font><br></h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 20px;padding-right: 18px;padding-bottom: 0;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p> Hello {CONTACT_FIRST_NAME},<br></p><p>Here is a proposal for you.</p><p></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%; text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-size: 16px; padding: 10px; text-size-adjust: 100%;\"><a href=\"{PROPOSAL_URL}\" target=\"_blank\" style=\"font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;\">Show Proposal</a></td></tr></tbody></table></td></tr></tbody></table> <p></p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p> </p><p>Public URL: {PUBLIC_PROPOSAL_URL}</p><p><br></p><p>{SIGNATURE} </p></td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 'default', '', 0),
(27, 'project_completed', 'Project completed', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Project #{PROJECT_ID}</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\"><p style=\"\"><span style=\"line-height: 18.5714px;\">The Project #{PROJECT_ID}&nbsp;has been closed by&nbsp;</span><span style=\"line-height: 18.5714px;\">{USER_NAME}</span></p><p style=\"\"><span style=\"line-height: 18.5714px;\">Title:&nbsp;</span>{PROJECT_TITLE}</p> <p style=\"\"><br></p> <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{PROJECT_URL}\" target=\"_blank\">Show Project</a></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"color: rgb(78, 94, 106); font-size: 13.5px;\">{SIGNATURE}</span><br></span></p>   </div>  </div> </div>', '', 'default', '', 0),
(28, 'proposal_accepted', 'Proposal accepted', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>PROPOSAL #{PROPOSAL_ID}</h1></div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">  <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">The proposal #{PROPOSAL_ID} has been accepted.</span><br></p><p style=\"\"><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{PROPOSAL_URL}\" target=\"_blank\">Show Proposal</a></span></p><p style=\"\"><br></p>  </div> </div></div>', '', 'default', '', 0),
(29, 'proposal_rejected', 'Proposal rejected', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>PROPOSAL #{PROPOSAL_ID}</h1></div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">  <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">The proposal #{PROPOSAL_ID} has been rejected.</span><br></p><p style=\"\"><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{PROPOSAL_URL}\" target=\"_blank\">Show Proposal</a></span></p><p style=\"\"><br></p>  </div> </div></div>', '', 'default', '', 0),
(30, 'estimate_commented', 'Estimate  #{ESTIMATE_ID}', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Estimate #{ESTIMATE_ID} Replies</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\"><p style=\"\"><span style=\"line-height: 18.5714px;\">{COMMENT_CONTENT}</span></p><p style=\"\"><span style=\"line-height: 18.5714px;\"><br></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{ESTIMATE_URL}\" target=\"_blank\">Show Estimate</a></span></p></div></div></div>', '', 'default', '', 0);
INSERT INTO `email_templates` (`id`, `template_name`, `email_subject`, `default_message`, `custom_message`, `template_type`, `language`, `deleted`) VALUES
(31, 'contract_sent', 'Contract sent', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 40px 18px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"> <h2 style=\"display: block; margin: 0px; padding: 0px; line-height: 100%; text-align: center;\"><font color=\"#ffffff\" face=\"Arial\"><span style=\"letter-spacing: -1px;\"><b>CONTRACT #{CONTRACT_ID}</b></span></font><br></h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 20px;padding-right: 18px;padding-bottom: 0;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p> Hello {CONTACT_FIRST_NAME},<br></p><p>Here is a contract for you.</p><p></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%; text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-size: 16px; padding: 10px; text-size-adjust: 100%;\"><a href=\"{CONTRACT_URL}\" target=\"_blank\" style=\"font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;\">Show Contract</a></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p>Public URL: {PUBLIC_CONTRACT_URL}<br></p><p><br></p><p>{SIGNATURE}<br></p></td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 'default', '', 0),
(32, 'contract_accepted', 'Contract accepted', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>CONTRACT #{CONTRACT_ID}</h1></div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">  <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">The contract #{CONTRACT_ID} has been accepted.</span><br></p><p style=\"\"><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{CONTRACT_URL}\" target=\"_blank\">Show Contract</a></span></p><p style=\"\"><br></p>  </div> </div></div>', '', 'default', '', 0),
(33, 'contract_rejected', 'Contract rejected', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>CONTRACT #{CONTRACT_ID}</h1></div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">  <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">The contract #{CONTRACT_ID} has been rejected.</span><br></p><p style=\"\"><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{CONTRACT_URL}\" target=\"_blank\">Show Contract</a></span></p><p style=\"\"><br></p>  </div> </div></div>', '', 'default', '', 0),
(34, 'invoice_manual_payment_added', 'Manual payment added', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 40px;padding-right: 18px;padding-bottom: 40px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> <h2 style=\"display: block;margin: 0;padding: 0;font-family: Arial;font-size: 30px;font-style: normal;font-weight: bold;line-height: 100%;letter-spacing: -1px;text-align: center;color: #ffffff !important;\">Payment Added</h2> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 20px;padding-right: 18px;padding-bottom: 0;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p> Hello,</p><p>A new payment has been added to {INVOICE_ID}. </p><p>Payment amount: {PAYMENT_AMOUNT}</p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td style=\"padding-top: 15px;padding-right: 0x;padding-bottom: 15px;padding-left: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td align=\"center\" valign=\"middle\" style=\"font-family: Arial;font-size: 16px;padding: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <a href=\"{INVOICE_URL}\" target=\"_blank\" style=\"font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;display: block;\">View Invoice</a> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> </td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 'default', '', 0),
(35, 'subscription_request_sent', 'New subscription request', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h2>{SUBSCRIPTION_TITLE}</h2></div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">  <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello {CONTACT_FIRST_NAME},</span><br></p><p style=\"\"><span style=\"font-size: 14px;\">You have a new subscription request. Please click here to see the subscription.</span></p><p style=\"\"><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{SUBSCRIPTION_URL}\" target=\"_blank\">Show Subscription</a></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>  </div> </div></div>', '', 'default', '', 0),
(36, 'announcement_created', '{ANNOUNCEMENT_TITLE}', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Announcement: {ANNOUNCEMENT_TITLE}</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\"><p style=\"\"><span style=\"line-height: 18.5714px;\">A new announcement has been created by {USER_NAME}.</span></p><p style=\"\"><span style=\"line-height: 18.5714px;\"><br></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{ANNOUNCEMENT_URL}\" target=\"_blank\">Show Announcement</a></span></p></div></div></div>', '', 'default', '', 0),
(37, 'task_general', '{TASK_TITLE} (Task #{TASK_ID})', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>{EVENT_TITLE}</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\"><p style=\"\"><span style=\"line-height: 18.5714px;\"><b>Task:</b> #</span><span style=\"font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);\">{TASK_ID} -&nbsp;</span><span style=\"font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);\">{TASK_TITLE}</span></p><p style=\"\"><span style=\"line-height: 18.5714px;\"><b>{CONTEXT_LABEL}:</b>&nbsp;</span>{CONTEXT_TITLE}</p> <p style=\"\"><br></p> <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{TASK_URL}\" target=\"_blank\">Show Task&nbsp;</a></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"color: rgb(78, 94, 106); font-size: 13.5px;\">{SIGNATURE}</span><br></span></p>   </div>  </div> </div>', '', 'default', '', 0),
(38, 'task_assigned', '{TASK_TITLE} (Task #{TASK_ID})', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Task assigned</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\"><p style=\"\"><span style=\"line-height: 18.5714px;\"><b>{USER_NAME}</b>  Assigned a task to <b>{ASSIGNED_TO_USER_NAME}</b></span></p><p style=\"\"><span style=\"line-height: 18.5714px;\"><b>Task:</b> #</span><span style=\"font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);\">{TASK_ID} -&nbsp;</span><span style=\"font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);\">{TASK_TITLE}</span></p><p style=\"\"><span style=\"line-height: 18.5714px;\"><b>{CONTEXT_LABEL}:</b>&nbsp;</span>{CONTEXT_TITLE}</p> <p style=\"\"><br></p> <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{TASK_URL}\" target=\"_blank\">Show Task&nbsp;</a></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"color: rgb(78, 94, 106); font-size: 13.5px;\">{SIGNATURE}</span><br></span></p>   </div>  </div> </div>', '', 'default', '', 0),
(39, 'task_commented', '{TASK_TITLE} (Task #{TASK_ID})', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Task commented</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\"><p style=\"\"><span style=\"line-height: 18.5714px;\"><b>{USER_NAME}</b>  Commented on a task.</span></p><p style=\"\"><span style=\"line-height: 18.5714px;\"><b>Task:</b> #</span><span style=\"font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);\">{TASK_ID} -&nbsp;</span><span style=\"font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align);\">{TASK_TITLE}</span></p><p style=\"\"><span style=\"line-height: 18.5714px;\"><b>{CONTEXT_LABEL}:</b>&nbsp;</span>{CONTEXT_TITLE}</p> <p style=\"\"><br></p> <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{TASK_URL}\" target=\"_blank\">Show Task&nbsp;</a></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"color: rgb(78, 94, 106); font-size: 13.5px;\">{SIGNATURE}</span><br></span></p>   </div>  </div> </div>', '', 'default', '', 0),
(40, 'subscription_started', 'Started a subscription', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h2>{SUBSCRIPTION_TITLE}</h2></div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">  <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello {CONTACT_FIRST_NAME},</span><br></p><p style=\"\"><span style=\"font-size: 14px;\">A new subscription has been started.&nbsp;</span></p><p style=\"\"><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{SUBSCRIPTION_URL}\" target=\"_blank\">Show Subscription</a></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>  </div> </div></div>', '', 'default', '', 0),
(41, 'subscription_invoice_created_via_cron_job', 'New invoice generated from subscription', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>INVOICE #{INVOICE_ID}</h1></div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">  <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello {CONTACT_FIRST_NAME},</span><br></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\">Thank you for your business cooperation.</span><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Your invoice for the subscription {SUBSCRIPTION_TITLE} has been generated and is attached here.</span></p><p style=\"\"><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{INVOICE_URL}\" target=\"_blank\">Show Invoice</a></span></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\">Invoice balance due is {BALANCE_DUE}</span><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Please pay this invoice within {DUE_DATE}.&nbsp;</span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>  </div> </div></div>', '', 'default', '', 0),
(42, 'send_credit_note', 'New credit note', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>CREDIT NOTE #{CREDIT_NOTE_ID}</h1></div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">  <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello {CONTACT_FIRST_NAME},</span><br></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\">Your invoice {INVOICE_ID} has been credited.&nbsp;</span><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Here is the credit note.&nbsp;</span></p><p style=\"\"><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{CREDIT_NOTE_URL}\" target=\"_blank\">Show Credit Note</a></span></p><p style=\"\"><br></p><p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>  </div> </div></div>', '', 'default', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `emp_code` varchar(140) NOT NULL,
  `first_name` varchar(140) NOT NULL,
  `last_name` varchar(140) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT 0,
  `joining_date` date NOT NULL,
  `phone_no` varchar(13) NOT NULL,
  `mobile_no` varchar(13) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `marital_status` tinyint(1) NOT NULL DEFAULT 0,
  `blood_group` varchar(5) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `qualification` varchar(255) NOT NULL,
  `years_of_exp` tinyint(4) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `w_hr_salary` decimal(14,2) NOT NULL,
  `ot_hr_salary` decimal(14,2) NOT NULL,
  `salary` decimal(14,2) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `emp_code`, `first_name`, `last_name`, `designation_id`, `gender`, `joining_date`, `phone_no`, `mobile_no`, `email`, `date_of_birth`, `marital_status`, `blood_group`, `address`, `city`, `state`, `country`, `zipcode`, `qualification`, `years_of_exp`, `status`, `w_hr_salary`, `ot_hr_salary`, `salary`, `created_at`, `created_by`) VALUES
(13, 'EP03', 'test', 'test', 3, 1, '2024-01-10', '1234567890', '', 'support@qbrainstorm.com', '2024-01-16', 0, 'O+', 'chennai', 'Thiruvallur', 'tamilnadu', 'India', '602024', 'bca', 2, 0, '5210000.00', '55440.00', '747441.00', '1704175462', 1),
(14, 'EP01', 'Raju', 'Kumar', 1, 0, '2024-01-03', '9845612304', '456123789', 'rajukumar@gmail.com', '2024-01-03', 0, 'O+', 'Delete Address2', 'chennai2', 'Tamil Nadu', 'India', '600087', 'MSC', 12, 0, '78000.00', '68000.00', '58000.00', '1704179913', 1),
(15, 'EP02', 'Raju', 'Kumar', 3, 1, '2024-01-05', '9845612302', '', 'raju@gmail.com', '2024-01-03', 1, 'O+', 'Delete Address2', 'chennai2', 'Tamil Nadu', 'India', '600087', 'MSC', 12, 0, '78000.00', '68000.00', '58000.00', '1704197974', 1),
(16, 'EMP005', 'QBS', 'Support', 3, 0, '2024-01-19', '09080780700', '9845612304', 'test@qbrainstorm.com', '2000-04-06', 0, 'O+', 'Test Address', 'chennai', 'Tamil Nadu', 'India', '600087', 'MSC', 12, 0, '78000.00', '68000.00', '58000.00', '1705663000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `emp_attendance`
--

CREATE TABLE `emp_attendance` (
  `attend_id` bigint(20) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `rec_date` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `work_hours` tinyint(4) NOT NULL,
  `ot_hours` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_attendance`
--

INSERT INTO `emp_attendance` (`attend_id`, `employee_id`, `rec_date`, `status`, `work_hours`, `ot_hours`) VALUES
(1, 1, '2022-04-01', 0, 8, 2),
(2, 2, '2022-04-01', 0, 12, 0),
(3, 13, '2024-01-01', 2, 12, 3),
(4, 13, '2022-01-02', 0, 12, 3),
(5, 2, '2022-04-02', 0, 12, 2),
(6, 3, '2022-04-02', 2, 0, 0),
(7, 13, '2024-01-09', 0, 1, 2),
(8, 13, '2024-01-19', 2, 8, 2);

-- --------------------------------------------------------

--
-- Table structure for table `equipments`
--

CREATE TABLE `equipments` (
  `equip_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `code` varchar(140) NOT NULL,
  `model` varchar(140) NOT NULL,
  `maker` varchar(255) NOT NULL,
  `bought_date` date NOT NULL,
  `age` varchar(140) NOT NULL,
  `work_type` varchar(50) NOT NULL,
  `consump_type` varchar(50) NOT NULL,
  `consumption` varchar(140) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipments`
--

INSERT INTO `equipments` (`equip_id`, `name`, `code`, `model`, `maker`, `bought_date`, `age`, `work_type`, `consump_type`, `consumption`, `status`, `description`, `created_at`, `created_by`, `updated_at`) VALUES
(1, 'test', 'EQ123', 'ABC-0001', 'test', '2022-03-27', '', 'Manual', 'Electric', '', 0, 'hello', '1650267326', 1, ''),
(4, 'test', 'EQ125', 'ABC-0069', 'test', '2022-04-02', '', 'Manual', 'Fuel', '4', 2, 'hello', '1650267407', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `erpcontract`
--

CREATE TABLE `erpcontract` (
  `contract_id` int(100) NOT NULL,
  `content` longtext DEFAULT NULL,
  `description` text DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `client` int(11) NOT NULL,
  `datestart` date DEFAULT NULL,
  `dateend` date DEFAULT NULL,
  `contract_type` int(11) DEFAULT NULL,
  `project_id` int(100) NOT NULL,
  `addedfrom` int(11) NOT NULL,
  `dateadded` datetime NOT NULL DEFAULT current_timestamp(),
  `isexpirynotified` int(11) NOT NULL,
  `contract_value` int(11) DEFAULT NULL,
  `trash` tinyint(1) NOT NULL,
  `not_visible_to_client` tinyint(1) NOT NULL,
  `hash` varchar(32) DEFAULT NULL,
  `signed` tinyint(1) NOT NULL,
  `signature` longtext DEFAULT NULL,
  `marked_as_signed` tinyint(1) NOT NULL,
  `acceptance_firstname` varchar(50) DEFAULT NULL,
  `acceptance_lastname` varchar(50) DEFAULT NULL,
  `acceptance_email` varchar(100) DEFAULT NULL,
  `acceptance_date` date DEFAULT NULL,
  `acceptance_ip` varchar(40) DEFAULT NULL,
  `short_link` varchar(100) DEFAULT NULL,
  `last_sent_at` datetime DEFAULT NULL,
  `contacts_sent_to` text DEFAULT NULL,
  `last_sign_reminder_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `erpcontract`
--

INSERT INTO `erpcontract` (`contract_id`, `content`, `description`, `subject`, `client`, `datestart`, `dateend`, `contract_type`, `project_id`, `addedfrom`, `dateadded`, `isexpirynotified`, `contract_value`, `trash`, `not_visible_to_client`, `hash`, `signed`, `signature`, `marked_as_signed`, `acceptance_firstname`, `acceptance_lastname`, `acceptance_email`, `acceptance_date`, `acceptance_ip`, `short_link`, `last_sent_at`, `contacts_sent_to`, `last_sign_reminder_at`) VALUES
(31, 'yes  it is', 'The Description', 'Contract_1', 46, '2024-06-03', '2024-06-14', 83, 0, 1, '2024-06-03 10:37:02', 0, 50000, 1, 1, NULL, 1, 'data:image/svg+xml,<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?><!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\"><svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" width=\"231\" height=\"78\"><path fill=\"none\" stroke=\"#000000\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M 40 21 c 0 0.14 -0.68 5.66 0 8 c 1.49 5.1 5.94 10.71 7 16 c 1.45 7.27 3.87 23.94 1 24 c -6.26 0.14 -43.44 -21.01 -47 -23 c -0.28 -0.16 2.67 -0.99 4 -1 c 32.59 -0.31 86.97 0.85 101 0 c 0.88 -0.05 -0.84 -4.63 -2 -6 c -2.1 -2.48 -6.78 -4.33 -9 -7 c -2.44 -2.93 -3.79 -7.68 -6 -11 c -1 -1.5 -3.39 -2.48 -4 -4 c -1.78 -4.44 -2.47 -13.45 -4 -16 c -0.47 -0.79 -4.1 0.03 -5 1 c -2.68 2.89 -6.79 8.77 -8 13 c -1.19 4.16 -0.25 10.09 0 15 c 0.08 1.67 0.3 3.6 1 5 c 1.48 2.97 5.15 5.96 6 9 c 1.26 4.5 1.35 14.49 1 16 c -0.11 0.47 -3.2 -1.89 -4 -3 c -0.66 -0.92 -1.49 -3.16 -1 -4 c 1.34 -2.3 5.11 -7.3 8 -8 c 6.38 -1.55 17.16 -0.51 25 0 c 1.98 0.13 4.8 0.8 6 2 c 1.95 1.95 3.62 6 5 9 c 0.55 1.2 0.44 2.87 1 4 c 0.68 1.37 1.94 3.79 3 4 c 1.54 0.31 5.85 -0.64 7 -2 c 1.85 -2.19 3.21 -7.44 4 -11 c 0.47 -2.13 0.47 -4.97 0 -7 c -0.45 -1.95 -1.83 -4.25 -3 -6 c -0.74 -1.11 -1.87 -2.57 -3 -3 c -2.71 -1.04 -6.77 -1.84 -10 -2 c -3.18 -0.16 -8.99 -0.46 -10 1 c -1.21 1.75 0.02 8.88 1 12 c 0.46 1.47 2.55 3.13 4 4 c 1.62 0.97 3.99 1.74 6 2 c 5.42 0.71 12.31 1.61 17 1 c 1.99 -0.26 4.87 -2.36 6 -4 c 1.49 -2.16 2.59 -6.31 3 -9 c 0.18 -1.17 -0.95 -4.1 -1 -4 c -0.07 0.15 -0.59 5.65 0 6 c 0.57 0.34 3.65 -1.8 5 -3 c 1.49 -1.32 2.77 -3.24 4 -5 c 1.12 -1.6 2.39 -3.31 3 -5 c 0.64 -1.76 1.03 -6.1 1 -6 c -0.1 0.33 -3.71 12.82 -5 19 c -0.33 1.57 -0.32 3.54 0 5 c 0.29 1.3 1.14 2.89 2 4 c 1.36 1.75 5.54 4.81 5 5 c -1.21 0.44 -16.28 -0.99 -16 -1 c 1.15 -0.02 48.69 0.75 66 0 c 1.11 -0.05 3.03 -1.95 3 -3 c -0.23 -7.75 -1.67 -21.28 -4 -31 c -1.54 -6.41 -5.33 -16.64 -8 -19 c -1.28 -1.13 -7.13 2.13 -9 4 c -1.87 1.87 -2.92 5.89 -4 9 c -1.6 4.6 -3.02 9.29 -4 14 c -0.68 3.25 -1 6.65 -1 10 c 0 7.01 2.1 16.62 1 21 c -0.4 1.59 -4.68 2.92 -7 3 c -25.6 0.84 -82.53 0.04 -84 0 c -0.87 -0.03 35.62 -2.39 50 -1 c 4 0.39 7.84 6.15 12 7 c 18.42 3.79 40.46 5.95 61 8 l 19 0\"/></svg>', 0, 'Devid', 'johns', 'support@qbrainstorm.com', '2024-06-03', '192.168.29.138', NULL, NULL, NULL, NULL),
(32, 'new message 22', 'jk', 'Contract_1', 37, '2024-06-03', '2024-06-29', 69, 0, 1, '2024-06-03 11:41:07', 0, 560000, 0, 0, NULL, 1, 'data:image/svg+xml,<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?><!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\"><svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" width=\"299\" height=\"76\"><path fill=\"none\" stroke=\"#000000\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M 60 29 l 1 1\"/><path fill=\"none\" stroke=\"#000000\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M 84 27 c -0.05 0.09 -2.32 3.31 -3 5 c -0.59 1.48 -0.17 3.75 -1 5 c -2.77 4.16 -6.96 10.45 -11 13 c -3.92 2.47 -10.7 3.56 -16 4 c -10.29 0.86 -21.68 0.29 -32 0 c -1.33 -0.04 -2.85 -0.43 -4 -1 c -2.01 -1.01 -4.55 -2.34 -6 -4 c -2.91 -3.32 -6.25 -7.97 -8 -12 c -1.36 -3.12 -1.59 -7.33 -2 -11 c -0.26 -2.3 -0.28 -4.74 0 -7 c 0.37 -2.98 0.72 -6.92 2 -9 c 1 -1.63 3.88 -3.13 6 -4 c 4.94 -2.02 10.71 -4.72 16 -5 c 25.27 -1.32 58.37 -1.49 80 0 c 2.49 0.17 5.14 3.81 7 6 c 1.62 1.92 3.27 4.56 4 7 c 2.14 7.12 3.94 15.4 5 23 c 0.58 4.2 1.31 9.94 0 13 c -1.21 2.83 -5.83 6 -9 8 c -2.84 1.79 -10.46 3.99 -10 4 c 6.23 0.18 113.69 0.89 147 0 c 1.01 -0.03 2.58 -3.52 2 -4 c -1.97 -1.64 -9.32 -4.93 -14 -6 c -6.49 -1.48 -13.85 -1.63 -21 -2 c -6.1 -0.31 -12.2 -0.26 -18 0 c -1.33 0.06 -3.79 0.37 -4 1 c -0.27 0.82 0.87 4.21 2 5 c 2.47 1.71 7.21 3.16 11 4 c 14.16 3.15 28.08 5.54 43 8 c 16.54 2.73 48 7 48 7\"/></svg>', 0, 'Devid', 'johns', 'support@qbrainstorm.com', '2024-06-11', '192.168.29.138', NULL, NULL, NULL, NULL),
(33, NULL, '', 'Front End', 0, '2024-06-10', '2024-06-11', 83, 0, 1, '2024-06-10 12:09:03', 0, 0, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, NULL, 'bfhjfbj', 'Front End', 0, '2024-06-10', '2024-06-11', 83, 0, 1, '2024-06-10 12:09:37', 0, 1200, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, NULL, 'asddas', 'Front End', 0, '2024-06-10', '2024-06-26', 73, 0, 1, '2024-06-10 12:17:55', 0, 100, 0, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, NULL, '4210rgre', 'Front End', 38, '2024-06-10', '2024-06-19', 68, 0, 1, '2024-06-10 12:23:50', 0, 1000, 1, 1, NULL, 1, 'data:image/svg+xml,<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?><!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\"><svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" width=\"408\" height=\"102\"><path fill=\"none\" stroke=\"#000000\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M 11 74 c 0.14 -0.17 6.51 -6.45 8 -10 c 2.51 -5.99 3.89 -13.89 5 -21 c 1.23 -7.93 1.05 -15.82 2 -24 c 0.76 -6.53 2.58 -17.74 3 -19 c 0.1 -0.29 1.95 2.67 2 4 c 0.55 14.31 -0.44 31.62 0 48 c 0.24 8.9 1.2 16.99 2 26 c 0.59 6.6 0.84 13.89 2 19 c 0.26 1.13 2.94 3.37 3 3 c 0.3 -1.9 0.64 -14.98 0 -22 c -0.33 -3.64 -1.26 -8.25 -3 -11 c -1.84 -2.91 -5.82 -6.09 -9 -8 c -3.09 -1.85 -7.48 -3.58 -11 -4 c -4.23 -0.51 -13.59 0.91 -14 1 c -0.15 0.03 3.33 0.94 5 1 c 6.88 0.26 14.26 0.88 21 0 c 8.25 -1.08 16.57 -3.65 25 -6 c 6.22 -1.74 12.13 -3.61 18 -6 c 4.85 -1.97 14.22 -7.24 14 -7 c -0.44 0.48 -19.99 12.5 -25 19 c -2.57 3.34 -0.14 13.37 -2 16 c -1.16 1.64 -7.9 1.53 -10 1 c -0.94 -0.23 -2.17 -2.89 -2 -4 c 0.38 -2.45 1.87 -7.05 4 -9 c 8.34 -7.62 20.89 -15.02 31 -23 c 2.59 -2.05 5.03 -4.59 7 -7 c 0.89 -1.09 1.81 -2.68 2 -4 c 0.42 -2.95 0.58 -7.28 0 -10 c -0.29 -1.36 -1.87 -3.03 -3 -4 c -1.01 -0.87 -3.25 -2.32 -4 -2 c -0.98 0.42 -2.77 3.4 -3 5 c -0.36 2.49 0.15 6.32 1 9 c 1.05 3.31 3.62 6.51 5 10 c 3 7.6 7.15 22.68 8 23 c 0.7 0.26 -0.51 -13.37 0 -20 c 0.49 -6.38 2.25 -19.11 3 -19 c 0.8 0.11 3.03 17.5 4 20 c 0.17 0.44 2.46 -1.2 3 -2 c 0.61 -0.92 0.21 -3.6 1 -4 c 1.74 -0.87 6.64 -0.21 9 -1 c 1.11 -0.37 2.61 -1.96 3 -3 c 0.46 -1.23 0.47 -3.89 0 -5 c -0.34 -0.8 -2.06 -1.88 -3 -2 c -1.33 -0.17 -5.14 0.86 -5 1 c 0.19 0.19 4.9 1.16 7 1 c 1.88 -0.14 4.58 -0.79 6 -2 c 4.91 -4.21 13.04 -16.25 15 -16 c 1.61 0.2 1.36 12.06 1 18 c -0.64 10.62 -3.93 31.44 -4 32 c -0.01 0.12 0.13 -5.04 1 -7 c 1.6 -3.61 4.71 -8.9 7 -11 c 0.88 -0.8 3.62 -0.34 5 0 c 1 0.25 1.97 1.61 3 2 c 1.46 0.55 3.33 0.86 5 1 l 7 0\"/><path fill=\"none\" stroke=\"#000000\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M 226 18 c 0 0.23 0.34 8.62 0 13 c -1.01 12.89 -3.18 25.1 -4 38 c -0.54 8.44 -0.27 16.8 0 25 c 0.06 1.67 0.77 5.33 1 5 c 1.57 -2.26 10.89 -20.5 17 -31 c 4.89 -8.41 9.55 -16.08 15 -24 c 5.09 -7.39 12.93 -18.09 16 -21 c 0.49 -0.47 2.35 1.86 3 3 c 1.86 3.25 3.09 7.61 5 11 c 1.01 1.8 2.5 3.95 4 5 c 1.48 1.04 4.02 1.87 6 2 c 7.52 0.5 16.19 0.62 24 0 c 4.66 -0.37 9.55 -1.52 14 -3 c 5.43 -1.81 10.73 -4.37 16 -7 c 4.19 -2.09 8.29 -4.52 12 -7 c 1.13 -0.76 2.93 -3.21 3 -3 c 0.1 0.31 -0.86 4.44 -2 6 c -3.82 5.26 -8.63 12.31 -14 16 c -9.61 6.61 -22.21 12.46 -34 17 c -18.55 7.13 -37.59 13.32 -57 18 c -18.1 4.36 -35.9 6.23 -55 9 c -23.85 3.46 -67.8 8.8 -69 9 c -0.24 0.04 9.37 0.63 14 0 c 52.14 -7.05 101.68 -15.44 156 -23 c 38.2 -5.31 110 -14 110 -14\"/></svg>', 0, 'Josmar', 'Consulting Engineering', 'vps2cm3qq3@elatter.com', '2024-06-10', '192.168.29.189', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `erpexpenses`
--

CREATE TABLE `erpexpenses` (
  `id` int(11) NOT NULL,
  `exp_name` varchar(100) NOT NULL,
  `category` int(11) NOT NULL,
  `currency` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `tax` int(11) DEFAULT NULL,
  `tax2` int(11) NOT NULL DEFAULT 0,
  `reference_no` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `expense_name` varchar(191) DEFAULT NULL,
  `clientid` int(11) NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `billable` int(11) NOT NULL DEFAULT 0,
  `invoice_id` int(11) DEFAULT NULL,
  `paymentmode` varchar(50) DEFAULT NULL,
  `date` date NOT NULL,
  `dateadded` date NOT NULL DEFAULT current_timestamp(),
  `addedfrom` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `erpexpenses`
--

INSERT INTO `erpexpenses` (`id`, `exp_name`, `category`, `currency`, `amount`, `tax`, `tax2`, `reference_no`, `note`, `expense_name`, `clientid`, `project_id`, `billable`, `invoice_id`, `paymentmode`, `date`, `dateadded`, `addedfrom`) VALUES
(40, 'last dup', 9, 11, '50000.00', 2, 2, '', 'note', NULL, 45, 25, 1, 19, '1', '2024-06-06', '2024-06-06', 1),
(41, 'Lst Expense', 3, 6, '5000.00', 1, 3, '', 'Nothing', NULL, 37, 23, 1, 20, '1', '2024-06-10', '2024-06-10', 1),
(42, 'Exp last', 1, 11, '4000.00', 1, 1, '', 'note', NULL, 37, 23, 0, 21, '', '2024-06-12', '2024-06-11', 1),
(44, 'test', 1, 6, '10000.00', 0, 0, '', '1000', NULL, 37, 23, 1, 23, '', '2024-11-02', '2024-11-02', 1),
(45, 'Today test 1', 8, 6, '35000.00', 0, 0, '', 'asasdsadsad', NULL, 37, 23, 1, NULL, '', '2023-11-14', '2024-11-14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `erp_companyinfo`
--

CREATE TABLE `erp_companyinfo` (
  `id` int(11) NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `company_logo` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `zipcode` int(11) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `vat_number` varchar(255) NOT NULL,
  `license_number` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `erp_companyinfo`
--

INSERT INTO `erp_companyinfo` (`id`, `company_name`, `company_logo`, `address`, `city`, `state`, `country`, `zipcode`, `phone_number`, `vat_number`, `license_number`, `created_at`, `updated_at`) VALUES
(1, 'Q Brainstorm Software', '1707110052_b4b496e1d7ddda887343.png', 'No.164, First Floor, Arcot Rd', ' Valasaravakkam, chennai', 'Tamil Nadu ', 'India', 600089, '9080780700', '1234567891', '#12345 ', '2024-02-03 07:25:33', '2024-02-03 07:25:33');

-- --------------------------------------------------------

--
-- Table structure for table `erp_expenses_categories`
--

CREATE TABLE `erp_expenses_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `description` text NOT NULL,
  `dateadded` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `erp_expenses_categories`
--

INSERT INTO `erp_expenses_categories` (`id`, `name`, `description`, `dateadded`) VALUES
(1, 'Travel', 'Demo', '2024-06-03'),
(2, 'Rent', 'Demo_1', '2024-06-03'),
(3, 'Product', '', '2024-06-03'),
(8, 'Purchase', '', '2024-06-03'),
(9, 'data', '', '2024-06-03');

-- --------------------------------------------------------

--
-- Table structure for table `erp_goals`
--

CREATE TABLE `erp_goals` (
  `goals_id` int(200) NOT NULL,
  `subject` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `goal_type` int(11) NOT NULL,
  `contract_type` int(11) NOT NULL,
  `achievement` int(11) NOT NULL,
  `notify_when_fail` int(11) NOT NULL,
  `notify_when_achieve` int(11) NOT NULL,
  `notified` int(11) NOT NULL DEFAULT 0,
  `staff_id` int(11) NOT NULL,
  `dateadded` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `erp_goals`
--

INSERT INTO `erp_goals` (`goals_id`, `subject`, `description`, `start_date`, `end_date`, `goal_type`, `contract_type`, `achievement`, `notify_when_fail`, `notify_when_achieve`, `notified`, `staff_id`, `dateadded`) VALUES
(6, 'New Goals with staff', 'sadsad', '2024-04-02', '2024-11-09', 2, 0, 2, 1, 1, 1, 1, '2024-06-14'),
(9, 'Second Goals', 'test', '2024-06-14', '2024-06-22', 1, 0, 0, 1, 1, 0, 1, '2024-06-14'),
(10, 'test', 'test', '2024-02-29', '2024-11-30', 2, 0, 5, 0, 0, 0, 0, '2024-11-11');

-- --------------------------------------------------------

--
-- Table structure for table `erp_groups`
--

CREATE TABLE `erp_groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(100) NOT NULL,
  `related_to` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `erp_groups`
--

INSERT INTO `erp_groups` (`group_id`, `group_name`, `related_to`) VALUES
(1, 'Construction', 'customer'),
(4, 'International', 'customer'),
(5, 'VIP', 'customer'),
(8, 'Domestic', 'customer'),
(9, 'Grade A', 'raw_material'),
(10, 'Grade B', 'raw_material'),
(11, 'Grade A', 'semi_finished'),
(12, 'Grade B', 'semi_finished'),
(13, 'Grade A', 'finished_good'),
(14, 'Grade B', 'finished_good'),
(15, 'Domestic', 'expense'),
(16, 'Supplier G1', 'supplier'),
(17, 'Supplier G2', 'supplier'),
(19, 'Serv B', 'inventory_service'),
(23, 'test', 'supplier');

-- --------------------------------------------------------

--
-- Table structure for table `erp_groups_map`
--

CREATE TABLE `erp_groups_map` (
  `groupmap_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `related_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `erp_groups_map`
--

INSERT INTO `erp_groups_map` (`groupmap_id`, `group_id`, `related_id`) VALUES
(10, 4, 7),
(19, 4, 8),
(20, 5, 8),
(21, 5, 9),
(22, 8, 9),
(23, 5, 10),
(26, 4, 12),
(91, 5, 38),
(92, 4, 38),
(101, 16, 6),
(102, 17, 7),
(103, 16, 7),
(127, 17, 20),
(128, 16, 21),
(130, 1, 24),
(131, 0, 24),
(132, 234234, 24),
(133, 0, 24),
(134, 234324, 24),
(135, 0, 24),
(136, 0, 24),
(137, 0, 24),
(138, 2147483647, 24),
(139, 0, 24),
(140, 0, 24),
(141, 600087, 24),
(142, 0, 24),
(143, 0, 24),
(144, 0, 24),
(145, 234324, 24),
(146, 3423, 24),
(147, 23434, 24),
(148, 1, 24),
(149, 1, 25),
(150, 0, 25),
(151, 234234, 25),
(152, 0, 25),
(153, 234324, 25),
(154, 0, 25),
(155, 0, 25),
(156, 0, 25),
(157, 2147483647, 25),
(158, 0, 25),
(159, 0, 25),
(160, 600087, 25),
(161, 0, 25),
(162, 0, 25),
(163, 0, 25),
(164, 234324, 25),
(165, 0, 25),
(166, 0, 25),
(167, 1, 25),
(168, 1, 26),
(169, 0, 26),
(170, 234234, 26),
(171, 0, 26),
(172, 234324, 26),
(173, 0, 26),
(174, 0, 26),
(175, 0, 26),
(176, 2147483647, 26),
(177, 0, 26),
(178, 0, 26),
(179, 600087, 26),
(180, 0, 26),
(181, 0, 26),
(182, 0, 26),
(183, 234324, 26),
(184, 234234, 26),
(185, 234234, 26),
(186, 1, 26),
(187, 1, 27),
(188, 0, 27),
(189, 0, 27),
(190, 0, 27),
(191, 0, 27),
(192, 0, 27),
(193, 0, 27),
(194, 0, 27),
(195, 908078070, 27),
(196, 0, 27),
(197, 0, 27),
(198, 600087, 27),
(199, 0, 27),
(200, 0, 27),
(201, 0, 27),
(202, 234324, 27),
(203, 0, 27),
(204, 0, 27),
(205, 1, 27),
(206, 16, 3),
(207, 1, 28),
(208, 0, 28),
(209, 0, 28),
(210, 0, 28),
(211, 345345, 28),
(212, 0, 28),
(213, 0, 28),
(214, 0, 28),
(215, 2147483647, 28),
(216, 0, 28),
(217, 345345, 28),
(218, 600087, 28),
(219, 345345, 28),
(220, 345345, 28),
(221, 0, 28),
(222, 345345, 28),
(223, 0, 28),
(224, 0, 28),
(225, 1, 28),
(226, 1, 29),
(227, 0, 29),
(228, 0, 29),
(229, 0, 29),
(230, 345345, 29),
(231, 0, 29),
(232, 0, 29),
(233, 0, 29),
(234, 2147483647, 29),
(235, 0, 29),
(236, 345345, 29),
(237, 600087, 29),
(238, 345345, 29),
(239, 345345, 29),
(240, 0, 29),
(241, 345345, 29),
(242, 0, 29),
(243, 0, 29),
(244, 1, 29),
(245, 1, 30),
(246, 0, 30),
(247, 0, 30),
(248, 0, 30),
(249, 345345, 30),
(250, 0, 30),
(251, 0, 30),
(252, 0, 30),
(253, 2147483647, 30),
(254, 0, 30),
(255, 345345, 30),
(256, 600087, 30),
(257, 345345, 30),
(258, 345345, 30),
(259, 0, 30),
(260, 345345, 30),
(261, 0, 30),
(262, 0, 30),
(263, 1, 30),
(264, 17, 31),
(265, 1, 32),
(266, 0, 32),
(267, 456456, 32),
(268, 0, 32),
(269, 456456, 32),
(270, 0, 32),
(271, 0, 32),
(272, 0, 32),
(273, 2147483647, 32),
(274, 0, 32),
(275, 0, 32),
(276, 600087, 32),
(277, 4456456, 32),
(278, 456456, 32),
(279, 0, 32),
(280, 456456, 32),
(281, 0, 32),
(282, 0, 32),
(283, 1, 32),
(296, 16, 39),
(297, 17, 39),
(298, 17, 40),
(299, 16, 40),
(300, 17, 41),
(301, 16, 42),
(302, 17, 43),
(303, 17, 44),
(304, 17, 45),
(305, 5, 41),
(306, 8, 43),
(307, 5, 43),
(308, 4, 43),
(309, 1, 43),
(310, 8, 44),
(311, 5, 44),
(312, 4, 44),
(313, 1, 44),
(316, 5, 11),
(317, 17, 46),
(318, 5, 6),
(319, 8, 6),
(320, 16, 47),
(321, 16, 48),
(322, 17, 49),
(323, 23, 50),
(324, 16, 51),
(325, 16, 52),
(326, 17, 1),
(327, 17, 2),
(331, 4, 46),
(332, 8, 47),
(334, 17, 5),
(335, 5, 37),
(336, 4, 37),
(337, 1, 37),
(338, 4, 45),
(339, 8, 45),
(340, 5, 48),
(341, 5, 49),
(342, 23, 4),
(343, 5, 50);

-- --------------------------------------------------------

--
-- Table structure for table `erp_jobqueue`
--

CREATE TABLE `erp_jobqueue` (
  `job_id` int(11) NOT NULL,
  `job_name` varchar(255) NOT NULL,
  `job_params` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `attempt` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `priority` tinyint(4) NOT NULL DEFAULT 0,
  `run_at` varchar(20) NOT NULL,
  `system` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `erp_jobqueue`
--

INSERT INTO `erp_jobqueue` (`job_id`, `job_name`, `job_params`, `attempt`, `status`, `priority`, `run_at`, `system`) VALUES
(1, 'notify', '{\"notify_id\":20}', 3, 5, 3, '1702256760', 0),
(2, 'notify', '{\"notify_id\":21}', 3, 5, 3, '1702576980', 0),
(3, 'notify', '{\"notify_id\":22}', 3, 5, 3, '1703074140', 0),
(4, 'notify', '{\"notify_id\":23}', 3, 5, 3, '1703074620', 0),
(5, 'notify', '{\"notify_id\":24}', 3, 5, 3, '1703082360', 0),
(6, 'notify', '{\"notify_id\":25}', 3, 5, 3, '1703428380', 0),
(9, 'notify', '{\"notify_id\":28}', 3, 5, 3, '1703087460', 0),
(10, 'notify', '{\"notify_id\":29}', 3, 5, 3, '1703524560', 0),
(11, 'notify', '{\"notify_id\":30}', 3, 5, 3, '1703524560', 0),
(12, 'notify', '{\"notify_id\":31}', 3, 5, 3, '1703591520', 0),
(13, 'notify', '{\"notify_id\":32}', 3, 5, 3, '1703594340', 0),
(14, 'notify', '{\"notify_id\":33}', 3, 5, 3, '1703594340', 0),
(15, 'notify', '{\"notify_id\":34}', 3, 5, 3, '1703594340', 0),
(16, 'notify', '{\"notify_id\":35}', 3, 5, 3, '1703594340', 0),
(17, 'notify', '{\"notify_id\":36}', 3, 5, 3, '1703595780', 0),
(18, 'notify', '{\"notify_id\":37}', 3, 5, 3, '1703509920', 0),
(22, 'notify', '{\"notify_id\":\"19\"}', 3, 5, 3, '1651901160', 0),
(23, 'notify', '{\"notify_id\":40}', 3, 5, 3, '1703714040', 0),
(28, 'notify', '{\"notify_id\":45}', 3, 5, 3, '1701724080', 0),
(29, 'notify', '{\"notify_id\":46}', 3, 5, 3, '1703195460', 0),
(32, 'rfqsend', '{\"supp_rfq_id\":\"5\"}', 3, 5, 3, '1703673627', 0),
(34, 'rfqsend', '{\"supp_rfq_id\":\"5\"}', 3, 5, 3, '1703677637', 0),
(40, 'notify', '{\"notify_id\":\"15\"}', 3, 5, 3, '1651931400', 0),
(41, 'notify', '{\"notify_id\":\"49\"}', 3, 5, 3, '1703782500', 0),
(42, 'notify', '{\"notify_id\":54}', 3, 5, 3, '1703871840', 0),
(44, 'notify', '{\"notify_id\":56}', 3, 5, 3, '1703891220', 0),
(45, 'notify', '{\"notify_id\":57}', 3, 5, 3, '1702501560', 0),
(46, 'notify', '{\"notify_id\":58}', 3, 5, 3, '1702328760', 0),
(49, 'notify', '{\"notify_id\":\"59\"}', 3, 5, 3, '1703891580', 0),
(50, 'notify', '{\"notify_id\":60}', 3, 5, 3, '1703200800', 0),
(51, 'notify', '{\"notify_id\":61}', 3, 5, 3, '1703719740', 0),
(53, 'notify', '{\"notify_id\":63}', 3, 5, 3, '1703547120', 0),
(54, 'notify', '{\"notify_id\":64}', 3, 5, 3, '1703111520', 0),
(56, 'notify', '{\"notify_id\":65}', 3, 5, 3, '1703759940', 0),
(57, 'notify', '{\"notify_id\":66}', 3, 5, 3, '1703846400', 0),
(61, 'notify', '{\"notify_id\":68}', 3, 5, 3, '1703934480', 0),
(62, 'notify', '{\"notify_id\":69}', 3, 5, 3, '1703934600', 0),
(63, 'notify', '{\"notify_id\":70}', 3, 5, 3, '1704021360', 0),
(65, 'notify', '{\"notify_id\":\"71\"}', 3, 5, 3, '1703848800', 0),
(67, 'notify', '{\"notify_id\":73}', 3, 5, 3, '1703935380', 0),
(69, 'notify', '{\"notify_id\":74}', 3, 5, 3, '1703936160', 0),
(70, 'notify', '{\"notify_id\":75}', 3, 5, 3, '1703849760', 0),
(72, 'notify', '{\"notify_id\":\"76\"}', 3, 5, 3, '1703849880', 0),
(73, 'notify', '{\"notify_id\":77}', 3, 5, 3, '1703997600', 0),
(77, 'notify', '{\"notify_id\":80}', 3, 5, 3, '1701724080', 0),
(80, 'notify', '{\"notify_id\":\"81\"}', 3, 5, 3, '1703851500', 0),
(81, 'notify', '{\"notify_id\":82}', 3, 5, 3, '1703701020', 0),
(82, 'notify', '{\"notify_id\":83}', 3, 5, 3, '1703873880', 0),
(83, 'notify', '{\"notify_id\":84}', 3, 5, 3, '1703877480', 0),
(84, 'notify', '{\"notify_id\":85}', 3, 5, 3, '1703960400', 0),
(85, 'notify', '{\"notify_id\":86}', 3, 5, 3, '1703704860', 0),
(86, 'notify', '{\"notify_id\":87}', 3, 5, 3, '1703946960', 0),
(87, 'notify', '{\"notify_id\":88}', 3, 5, 3, '1703947620', 0),
(88, 'notify', '{\"notify_id\":89}', 3, 5, 3, '1703948040', 0),
(89, 'notify', '{\"notify_id\":90}', 3, 5, 3, '1703948460', 0),
(90, 'notify', '{\"notify_id\":91}', 3, 5, 3, '1703948460', 0),
(91, 'notify', '{\"notify_id\":92}', 3, 5, 3, '1703948520', 0),
(92, 'notify', '{\"notify_id\":93}', 3, 5, 3, '1703948580', 0),
(93, 'notify', '{\"notify_id\":94}', 3, 5, 3, '1703948640', 0),
(94, 'notify', '{\"notify_id\":95}', 3, 5, 3, '1703950320', 0),
(95, 'notify', '{\"notify_id\":96}', 3, 5, 3, '1703869680', 0),
(96, 'rfqsend', '{\"supp_rfq_id\":\"14\"}', 3, 5, 3, '1704172781', 0),
(97, 'rfqsend', '{\"supp_rfq_id\":\"14\"}', 3, 5, 3, '1704173222', 0),
(98, 'rfqsend', '{\"supp_rfq_id\":\"14\"}', 3, 5, 3, '1704173395', 0),
(99, 'notify', '{\"notify_id\":97}', 3, 5, 3, '1704196260', 0),
(100, 'rfqsend', '{\"supp_rfq_id\":\"14\"}', 3, 5, 3, '1704188744', 0),
(102, 'notify', '{\"notify_id\":99}', 3, 5, 3, '1704265140', 0),
(103, 'notify', '{\"notify_id\":100}', 3, 5, 3, '1704378240', 0),
(104, 'notify', '{\"notify_id\":101}', 3, 5, 3, '1704470760', 0),
(105, 'notify', '{\"notify_id\":102}', 3, 5, 3, '1704986580', 0),
(106, 'notify', '{\"notify_id\":103}', 3, 5, 3, '1704986580', 0),
(107, 'notify', '{\"notify_id\":104}', 3, 5, 3, '1704904380', 0),
(108, 'notify', '{\"notify_id\":105}', 3, 5, 3, '1705166940', 0),
(109, 'notify', '{\"notify_id\":106}', 3, 5, 3, '1704477000', 0),
(113, 'notify', '{\"notify_id\":\"107\"}', 3, 5, 3, '1704550260', 0),
(114, 'rfqsend', '{\"supp_rfq_id\":\"1\"}', 3, 5, 3, '1704777800', 0),
(116, 'notify', '{\"notify_id\":111}', 3, 5, 3, '1704847200', 0),
(117, 'notify', '{\"notify_id\":112}', 3, 5, 3, '1704937380', 0),
(119, 'notify', '{\"notify_id\":114}', 3, 5, 3, '1705583460', 0),
(120, 'rfqsend', '{\"supp_rfq_id\":\"1\"}', 3, 5, 3, '1704970930', 0),
(121, 'rfqsend', '{\"supp_rfq_id\":\"1\"}', 3, 5, 3, '1705057291', 0),
(122, 'notify', '{\"notify_id\":115}', 3, 5, 3, '1705573680', 0),
(123, 'rfqsend', '{\"supp_rfq_id\":\"1\"}', 3, 5, 3, '1705743775', 0),
(124, 'rfqsend', '{\"supp_rfq_id\":\"1\"}', 3, 5, 3, '1705743787', 0),
(129, 'notify', '{\"notify_id\":118}', 3, 5, 3, '1706129040', 0),
(130, 'notify', '{\"notify_id\":119}', 3, 5, 3, '1706028540', 0),
(131, 'notify', '{\"notify_id\":120}', 3, 5, 3, '1706119920', 0),
(132, 'notify', '{\"notify_id\":121}', 3, 5, 3, '1706206380', 0),
(134, 'notify', '{\"notify_id\":123}', 3, 5, 3, '1706119440', 0),
(135, 'notify', '{\"notify_id\":\"122\"}', 3, 5, 3, '1706205420', 0),
(136, 'notify', '{\"notify_id\":124}', 3, 5, 3, '1706184420', 0),
(137, 'notify', '{\"notify_id\":125}', 3, 5, 3, '1706621100', 0),
(144, 'notify', '{\"notify_id\":\"5\"}', 3, 5, 3, '1711475820', 0),
(145, 'notify', '{\"notify_id\":6}', 3, 5, 3, '1711480020', 0),
(146, 'notify', '{\"notify_id\":15}', 3, 5, 3, '1718887260', 0),
(147, 'notify', '{\"notify_id\":16}', 3, 5, 3, '1718887500', 0),
(148, 'rfqsend', '{\"supp_rfq_id\":\"0\"}', 3, 5, 3, '1729233199', 0);

-- --------------------------------------------------------

--
-- Table structure for table `erp_log`
--

CREATE TABLE `erp_log` (
  `log_id` int(11) NOT NULL,
  `title` varchar(120) NOT NULL,
  `log_text` text NOT NULL,
  `ref_link` varchar(255) NOT NULL,
  `additional_info` varchar(1000) DEFAULT NULL,
  `done_by` varchar(120) NOT NULL,
  `created_at` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `erp_log`
--

INSERT INTO `erp_log` (`log_id`, `title`, `log_text`, `ref_link`, `additional_info`, `done_by`, `created_at`) VALUES
(2600, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-02-05 07:11:19'),
(2601, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-05 07:11:25'),
(2602, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-05 12:04:13'),
(2603, 'Estimate Update', '[ Estimate successfully updated ]', 'erp/sale/estimateview/41', '', 'Qbs ', '2024-02-05 12:48:27'),
(2604, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/10', '', 'Qbs ', '2024-02-05 12:59:37'),
(2605, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/10', '', 'Qbs ', '2024-02-05 12:59:37'),
(2606, 'Estimate Insert', '[ Estimate successfully created ]', 'erp/sale/estimateview/44', '', 'Qbs ', '2024-02-05 13:27:14'),
(2607, 'Estimate Insert', '[ Estimate successfully created ]', 'erp/sale/estimateview/49', '', 'Qbs ', '2024-02-05 13:49:30'),
(2608, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-06 05:16:21'),
(2609, 'Estimate Deletion', '[ Estimate successfully deleted ]', 'erp/sale/delete_estimate/42', '{\"estimate_id\":\"42\",\"code\":\"e-2\",\"estimate_date\":\"2024-01-22\",\"terms_condition\":\"fsf\",\"name\":\"jacob\",\"cust_id\":\"46\",\"shippingaddr_id\":\"0\"}', 'Qbs ', '2024-02-06 05:17:05'),
(2610, 'Estimate Insert', '[ Estimate successfully created ]', 'erp/sale/estimateview/50', '', 'Qbs ', '2024-02-06 05:25:12'),
(2611, 'Estimate Update', '[ Estimate successfully updated ]', 'erp/sale/estimateview/50', '', 'Qbs ', '2024-02-06 05:29:15'),
(2612, 'Estimate Update', '[ Estimate successfully updated ]', 'erp/sale/estimateview/50', '', 'Qbs ', '2024-02-06 05:29:57'),
(2613, 'Quotation Insert', '[ Quotation successfully created ]', 'erp/sale/quotationview/11', '', 'Qbs ', '2024-02-06 05:44:16'),
(2614, 'Quotation Insert', '[ Quotation successfully created ]', 'erp/sale/quotationview/13', '', 'Qbs ', '2024-02-06 05:52:47'),
(2615, 'Quotation Insert', '[ Quotation successfully created ]', 'erp/sale/quotationview/14', '', 'Qbs ', '2024-02-06 06:01:59'),
(2616, 'Quotation Insert', '[ Quotation successfully created ]', 'erp/sale/quotationview/1', '', 'Qbs ', '2024-02-06 06:03:11'),
(2617, 'Quotation Insert', '[ Quotation successfully created ]', 'erp/sale/quotationview/2', '', 'Qbs ', '2024-02-06 06:04:56'),
(2618, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/1', '', 'Qbs ', '2024-02-06 06:06:55'),
(2619, 'Quotation Insert', '[ Quotation successfully created ]', 'erp/sale/quotationview/3', '', 'Qbs ', '2024-02-06 06:09:21'),
(2620, 'Quotation Insert', '[ Quotation successfully created ]', 'erp/sale/quotationview/4', '', 'Qbs ', '2024-02-06 06:11:57'),
(2621, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/2', '', 'Qbs ', '2024-02-06 06:14:08'),
(2622, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/2', '', 'Qbs ', '2024-02-06 06:18:14'),
(2623, 'Sale Order Insert', '[ Sale Order successfully created ]', 'erp/sale/orderview/1', '', 'Qbs ', '2024-02-06 06:46:32'),
(2624, 'Sale Order Update', '[ Sale Order failed to update ]', 'erp/sale/orderview/1', '', 'Qbs ', '2024-02-06 06:53:19'),
(2625, 'Sale Order Update', '[ Sale Order failed to update ]', 'erp/sale/orderview/1', '', 'Qbs ', '2024-02-06 06:54:08'),
(2626, 'Sale Order Update', '[ Sale Order failed to update ]', 'erp/sale/orderview/1', '', 'Qbs ', '2024-02-06 06:55:17'),
(2627, 'Sale Order Update', '[ Sale Order failed to update ]', 'erp/sale/orderview/1', '', 'Qbs ', '2024-02-06 06:55:34'),
(2628, 'Sale Order Update', '[ Sale Order failed to update ]', 'erp/sale/orderview/1', '', 'Qbs ', '2024-02-06 06:55:49'),
(2629, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/75', '', 'Qbs ', '2024-02-06 07:37:40'),
(2630, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/1', '', 'Qbs ', '2024-02-06 07:38:52'),
(2631, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/1', '', 'Qbs ', '2024-02-06 07:49:51'),
(2632, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/1', '', 'Qbs ', '2024-02-06 07:51:08'),
(2633, 'Credit Note Insert', '[ Credit Note successfully created ]', 'http://localhost/erpcinew/public/erp/sales/credit_notes', '', 'Qbs ', '2024-02-06 08:05:14'),
(2634, 'Sale Order Update', '[ Sale Order failed to update ]', 'erp/sale/orderview/1', '', 'Qbs ', '2024-02-06 08:48:50'),
(2635, 'Sale Order Update', '[ Sale Order failed to update ]', 'erp/sale/orderview/1', '', 'Qbs ', '2024-02-06 08:49:09'),
(2636, 'Sale Order Update', '[ Sale Order failed to update ]', 'erp/sale/orderview/1', '', 'Qbs ', '2024-02-06 08:49:57'),
(2637, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/4', '', 'Qbs ', '2024-02-06 08:57:15'),
(2638, 'Sale Order Update', '[ Sale Order failed to update ]', 'erp/sale/orderview/1', '', 'Qbs ', '2024-02-06 08:57:33'),
(2639, 'Sale Order Update', '[ Sale Order failed to update ]', 'erp/sale/orderview/1', '', 'Qbs ', '2024-02-06 09:22:36'),
(2640, 'Sale Order Update', '[ Sale Order failed to update ]', 'erp/sale/orderview/1', '', 'Qbs ', '2024-02-06 09:31:18'),
(2641, 'Sale Order Update', '[ Sale Order failed to update ]', 'erp/sale/orderview/1', '', 'Qbs ', '2024-02-06 09:36:43'),
(2642, 'Sale Order Update', '[ Sale Order successfully updated ]', 'erp/sale/orderview/1', '', 'Qbs ', '2024-02-06 09:39:53'),
(2643, 'Sale Order Update', '[ Sale Order successfully updated ]', 'erp/sale/orderview/1', '', 'Qbs ', '2024-02-06 09:42:59'),
(2644, 'Sale Order Update', '[ Sale Order successfully updated ]', 'erp/sale/orderview/1', '', 'Qbs ', '2024-02-06 09:43:31'),
(2645, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/2', '', 'Qbs ', '2024-02-06 09:45:54'),
(2646, 'Sale Order Update', '[ Sale Order successfully updated ]', 'erp/sale/orderview/1', '', 'Qbs ', '2024-02-06 09:46:41'),
(2647, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/2', '', 'Qbs ', '2024-02-06 09:47:45'),
(2648, 'Credit Note Update', '[ Credit Note successfully updated ]', 'http://localhost/erpcinew/public/erp/sales/credit_notes', '', 'Qbs ', '2024-02-06 09:49:21'),
(2649, 'Credit Note Update', '[ Credit Note successfully updated ]', 'http://localhost/erpcinew/public/erp/sales/credit_notes', '', 'Qbs ', '2024-02-06 09:49:47'),
(2650, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/1', '', 'Qbs ', '2024-02-06 09:55:18'),
(2651, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/2', '', 'Qbs ', '2024-02-06 09:55:40'),
(2652, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/1', '', 'Qbs ', '2024-02-06 10:14:52'),
(2653, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/1', '', 'Qbs ', '2024-02-06 10:18:58'),
(2654, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/2', '', 'Qbs ', '2024-02-06 10:42:36'),
(2655, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/1', '', 'Qbs ', '2024-02-06 10:42:58'),
(2656, 'Credit Note Insert', '[ Credit Note successfully created ]', 'http://localhost/erpcinew/public/erp/sales/credit_notes', '', 'Qbs ', '2024-02-06 10:57:24'),
(2657, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/3', '', 'Qbs ', '2024-02-06 11:14:05'),
(2658, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/3', '', 'Qbs ', '2024-02-06 11:15:08'),
(2659, 'Credit Note Update', '[ Credit Note successfully updated ]', 'http://localhost/erpcinew/public/erp/sales/credit_notes', '', 'Qbs ', '2024-02-06 11:15:50'),
(2660, 'Credit Note Update', '[ Credit Note successfully updated ]', 'http://localhost/erpcinew/public/erp/sales/credit_notes', '', 'Qbs ', '2024-02-06 11:39:42'),
(2661, 'Sale Payment Update', '[ Sale Payment successfully updated ]', 'erp/sale/manage_view/3', '', 'Qbs ', '2024-02-06 12:16:29'),
(2662, 'Sale Payment Update', '[ Sale Payment successfully updated ]', 'erp/sale/manage_view/3', '', 'Qbs ', '2024-02-06 12:26:09'),
(2663, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/4', '', 'Qbs ', '2024-02-06 12:27:02'),
(2664, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/4', '', 'Qbs ', '2024-02-06 12:38:44'),
(2665, 'Sale Payment Delete', '[ Sale Payment successfully deleted ]', 'erp/sale/manage_view/4', '', 'Qbs ', '2024-02-06 12:40:46'),
(2666, 'Credit Note Update', '[ Credit Note successfully updated ]', 'http://localhost/erpcinew/public/erp/sales/credit_notes', '', 'Qbs ', '2024-02-06 12:42:51'),
(2667, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/5', '', 'Qbs ', '2024-02-06 13:28:36'),
(2668, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/5', '', 'Qbs ', '2024-02-06 13:29:04'),
(2669, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-08 04:42:11'),
(2670, 'Sale Order Insert', '[ Sale Order successfully created ]', 'erp/sale/orderview/2', '', 'Qbs ', '2024-02-08 04:43:44'),
(2671, 'Sale Order Insert', '[ Sale Order successfully created ]', 'erp/sale/orderview/3', '', 'Qbs ', '2024-02-08 04:44:58'),
(2672, 'Sale Order Insert', '[ Sale Order successfully created ]', 'erp/sale/orderview/4', '', 'Qbs ', '2024-02-08 05:47:10'),
(2673, 'Sale Order Insert', '[ Sale Order successfully created ]', 'erp/sale/orderview/5', '', 'Qbs ', '2024-02-08 05:47:51'),
(2674, 'Sale Order Insert', '[ Sale Order successfully created ]', 'erp/sale/orderview/6', '', 'Qbs ', '2024-02-08 05:48:26'),
(2675, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-09 05:06:42'),
(2676, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-09 05:08:50'),
(2677, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/6', '', 'Qbs ', '2024-02-09 05:09:29'),
(2678, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/7', '', 'Qbs ', '2024-02-09 05:38:42'),
(2679, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/7', '', 'Qbs ', '2024-02-09 05:43:59'),
(2680, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/7', '', 'Qbs ', '2024-02-09 05:46:12'),
(2681, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/6', '', 'Qbs ', '2024-02-09 05:46:35'),
(2682, 'Quotation Insert', '[ Quotation successfully created ]', 'erp/sale/quotationview/5', '', 'Qbs ', '2024-02-09 05:50:24'),
(2683, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-09 08:59:49'),
(2684, 'Customer Contact Insert', '[ Customer Contact successfully created ]', 'http://localhost/erpcinew/public/erp/crm/lead-customer-view/46', '', 'Qbs ', '2024-02-09 09:25:37'),
(2685, 'Customer Contact Insert', '[ Customer Contact successfully created ]', 'http://localhost/erpcinew/public/erp/crm/lead-customer-view/46', '', 'Qbs ', '2024-02-09 09:26:28'),
(2686, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://localhost/erpcinew/public/erp/crm/lead-customer-view/46', '', 'Qbs ', '2024-02-09 12:40:33'),
(2687, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-10 04:53:04'),
(2688, 'Customer Contact Insert', '[ Customer Contact successfully created ]', 'http://localhost/erpcinew/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-02-10 06:11:33'),
(2689, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-10 12:59:01'),
(2690, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-12 05:47:38'),
(2691, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/5', '', 'Qbs ', '2024-02-12 06:27:25'),
(2692, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-12 10:27:53'),
(2693, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-12 12:22:14'),
(2694, 'Customer Shipping Address Delete', '[ Customer Shipping Address successfully deleted ]', '/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-02-12 13:37:46'),
(2695, 'Customer Shipping Address Delete', '[ Customer Shipping Address successfully deleted ]', '/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-02-12 13:37:55'),
(2696, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-13 04:47:20'),
(2697, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-13 10:24:18'),
(2698, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-13 11:13:40'),
(2699, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/7', '', 'Qbs ', '2024-02-13 13:09:11'),
(2700, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/7', '', 'Qbs ', '2024-02-13 13:09:28'),
(2701, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-14 05:07:56'),
(2702, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-14 05:58:18'),
(2703, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-14 07:48:30'),
(2704, 'Customer Billing Address Insert', '[ Customer Billing Address successfully created ]', 'http://192.168.29.9/erpcinew/public/erp/crm/lead-customer-view/46', '', 'Qbs ', '2024-02-14 09:52:56'),
(2705, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/5', '', 'Qbs ', '2024-02-14 12:45:35'),
(2706, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/5', '', 'Qbs ', '2024-02-14 12:45:57'),
(2707, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/5', '', 'Qbs ', '2024-02-14 12:47:02'),
(2708, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/5', '', 'Qbs ', '2024-02-14 12:47:15'),
(2709, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/5', '', 'Qbs ', '2024-02-14 12:47:44'),
(2710, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/6', '', 'Qbs ', '2024-02-14 12:47:56'),
(2711, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/8', '', 'Qbs ', '2024-02-14 13:03:15'),
(2712, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/8', '', 'Qbs ', '2024-02-14 13:04:28'),
(2713, 'Estimate Insert', '[ Estimate successfully created ]', 'erp/sale/estimateview/52', '', 'Qbs ', '2024-02-14 13:06:26'),
(2714, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/7', '', 'Qbs ', '2024-02-14 13:19:10'),
(2715, 'Estimate Insert', '[ Estimate successfully created ]', 'erp/sale/estimateview/53', '', 'Qbs ', '2024-02-14 13:24:53'),
(2716, 'Estimate Update', '[ Estimate successfully updated ]', 'erp/sale/estimateview/52', '', 'Qbs ', '2024-02-14 13:31:14'),
(2717, 'Quotation Insert', '[ Quotation successfully created ]', 'erp/sale/quotationview/6', '', 'Qbs ', '2024-02-14 13:36:23'),
(2718, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/6', '', 'Qbs ', '2024-02-14 13:36:40'),
(2719, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/6', '', 'Qbs ', '2024-02-14 13:37:17'),
(2720, 'Sale Order Insert', '[ Sale Order successfully created ]', 'erp/sale/orderview/7', '', 'Qbs ', '2024-02-14 13:41:26'),
(2721, 'Sale Order Update', '[ Sale Order successfully updated ]', 'erp/sale/orderview/7', '', 'Qbs ', '2024-02-14 13:41:47'),
(2722, 'Sale Order Update', '[ Sale Order successfully updated ]', 'erp/sale/orderview/6', '', 'Qbs ', '2024-02-14 13:42:11'),
(2723, 'Sale Order Update', '[ Sale Order successfully updated ]', 'erp/sale/orderview/5', '', 'Qbs ', '2024-02-14 13:42:44'),
(2724, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-15 04:36:15'),
(2725, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/8', '', 'Qbs ', '2024-02-15 06:57:20'),
(2726, 'Estimate Insert', '[ Estimate successfully created ]', 'erp/sale/estimateview/54', '', 'Qbs ', '2024-02-15 09:34:31'),
(2727, 'Estimate Update', '[ Estimate successfully updated ]', 'erp/sale/estimateview/54', '', 'Qbs ', '2024-02-15 09:35:07'),
(2728, 'Estimate Update', '[ Estimate successfully updated ]', 'erp/sale/estimateview/54', '', 'Qbs ', '2024-02-15 10:09:28'),
(2729, 'Estimate Update', '[ Estimate successfully updated ]', 'erp/sale/estimateview/49', '', 'Qbs ', '2024-02-15 10:39:12'),
(2730, 'Quotation Insert', '[ Quotation successfully created ]', 'erp/sale/quotationview/7', '', 'Qbs ', '2024-02-15 11:06:08'),
(2731, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/7', '', 'Qbs ', '2024-02-15 11:53:14'),
(2732, 'Sale Order Insert', '[ Sale Order successfully created ]', 'erp/sale/orderview/8', '', 'Qbs ', '2024-02-15 12:16:17'),
(2733, 'Sale Order Update', '[ Sale Order successfully updated ]', 'erp/sale/orderview/8', '', 'Qbs ', '2024-02-15 12:23:30'),
(2734, 'Sale Order Update', '[ Sale Order successfully updated ]', 'erp/sale/orderview/8', '', 'Qbs ', '2024-02-15 12:24:32'),
(2735, 'Sale Order Update', '[ Sale Order successfully updated ]', 'erp/sale/orderview/5', '', 'Qbs ', '2024-02-15 12:33:48'),
(2736, 'Sale Order Update', '[ Sale Order successfully updated ]', 'erp/sale/orderview/5', '', 'Qbs ', '2024-02-15 12:33:59'),
(2737, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-16 04:58:35'),
(2738, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-17 04:56:26'),
(2739, 'Currency Update', '[ Currency successfully updated ]', 'erp/finance/currency/', '', 'Qbs ', '2024-02-17 05:26:03'),
(2740, 'Currency Delete', '[ Currency successfully deleted ]', 'erp/finance/journalentry/', '', 'Qbs ', '2024-02-17 05:26:41'),
(2741, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/5', '', 'Qbs ', '2024-02-17 07:30:19'),
(2742, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-19 05:30:53'),
(2743, 'Quotation Insert', '[ Quotation successfully created ]', 'erp/sale/quotationview/8', '', 'Qbs ', '2024-02-19 07:38:22'),
(2744, 'Quotation Deletion', '[ Quotation successfully deleted ]', 'erp.sale.quotation.delete8', '{\"quote_id\":\"8\",\"code\":\"PRO-008\",\"subject\":\"tes\",\"expiry_date\":\"2024-02-27\",\"quote_date\":\"2024-02-19\",\"cust_id\":\"45\",\"shippingaddr_id\":\"0\",\"currency_id\":\"8\",\"currency_place\":\"\",\"transport_req\":\"0\",\"trans_charge\":\"0.00\",\"discount\":\"0.00\",\"terms_condition\":\"<p>resrr<\\/p>\",\"payment_terms\":\"30 day\",\"status\":\"0\",\"created_at\":\"1708328301\",\"created_by\":\"1\"}', 'Qbs ', '2024-02-19 07:41:52'),
(2745, 'Quotation Insert', '[ Quotation successfully created ]', 'erp/sale/quotationview/20', '', 'Qbs ', '2024-02-19 09:44:35'),
(2746, 'Quotation Insert', '[ Quotation failed to create ]', 'erp.sale.quotation.add', '', 'Qbs ', '2024-02-19 09:51:44'),
(2747, 'Quotation Insert', '[ Quotation failed to create ]', 'erp.sale.quotation.add', '', 'Qbs ', '2024-02-19 09:57:24'),
(2748, 'Quotation Insert', '[ Quotation failed to create ]', 'erp.sale.quotation.add', '', 'Qbs ', '2024-02-19 10:00:37'),
(2749, 'Quotation Insert', '[ Quotation successfully created ]', 'erp/sale/quotationview/29', '', 'Qbs ', '2024-02-19 11:17:49'),
(2750, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/29', '', 'Qbs ', '2024-02-19 12:55:25'),
(2751, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/29', '', 'Qbs ', '2024-02-19 13:11:58'),
(2752, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/29', '', 'Qbs ', '2024-02-19 13:14:00'),
(2753, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-20 04:57:47'),
(2754, 'Currency Insert', '[ Currency successfully created ]', 'erp/finance/currency/', '', 'Qbs ', '2024-02-20 10:27:35'),
(2755, 'Currency Update', '[ Currency successfully updated ]', 'erp/finance/currency/', '', 'Qbs ', '2024-02-20 10:28:05'),
(2756, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/29', '', 'Qbs ', '2024-02-20 10:29:27'),
(2757, 'Quotation Deletion', '[ Quotation successfully deleted ]', 'erp.sale.quotation.delete29', '{\"quote_id\":\"29\",\"code\":\"PRO-021\",\"subject\":\"test\",\"expiry_date\":\"2024-02-27\",\"quote_date\":\"2024-02-19\",\"cust_id\":\"46\",\"shippingaddr_id\":\"0\",\"billingaddr_id\":\"0\",\"currency_id\":\"9\",\"currency_place\":\"after\",\"transport_req\":\"0\",\"trans_charge\":\"0.00\",\"discount\":\"1.00\",\"terms_condition\":\"<p>fsd<\\/p>\",\"payment_terms\":\"sdf\",\"status\":\"0\",\"created_at\":\"1708341469\",\"created_by\":\"1\"}', 'Qbs ', '2024-02-20 10:29:42'),
(2758, 'Quotation Insert', '[ Quotation successfully created ]', 'erp/sale/quotationview/30', '', 'Qbs ', '2024-02-20 10:30:50'),
(2759, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/30', '', 'Qbs ', '2024-02-20 10:33:13'),
(2760, 'Quotation Insert', '[ Quotation successfully created ]', 'erp/sale/quotationview/31', '', 'Qbs ', '2024-02-20 10:34:18'),
(2761, 'Quotation Insert', '[ Quotation successfully created ]', 'erp/sale/quotationview/1', '', 'Qbs ', '2024-02-20 10:35:26'),
(2762, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/1', '', 'Qbs ', '2024-02-20 10:36:14'),
(2763, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/1', '', 'Qbs ', '2024-02-20 10:36:44'),
(2764, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/1', '', 'Qbs ', '2024-02-20 10:36:56'),
(2765, 'Currency Update', '[ Currency failed to update ]', '', '', 'Qbs ', '2024-02-20 10:39:08'),
(2766, 'Currency Update', '[ Currency failed to update ]', '', '', 'Qbs ', '2024-02-20 10:39:20'),
(2767, 'Currency Update', '[ Currency failed to update ]', '', '', 'Qbs ', '2024-02-20 10:39:36'),
(2768, 'Currency Update', '[ Currency successfully updated ]', 'erp/finance/currency/', '', 'Qbs ', '2024-02-20 10:40:07'),
(2769, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/1', '', 'Qbs ', '2024-02-20 10:40:48'),
(2770, 'Customer Billing Address Insert', '[ Customer Billing Address successfully created ]', 'http://192.168.29.9/erpcinew/public/erp/crm/lead-customer-view/48', '', 'Qbs ', '2024-02-20 12:29:42'),
(2771, 'Customer Shipping Address Insert', '[ Customer Shipping Address successfully created ]', 'http://192.168.29.9/erpcinew/public/erp/crm/lead-customer-view/48', '', 'Qbs ', '2024-02-20 12:30:20'),
(2772, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-21 04:59:20'),
(2773, 'Currency Update', '[ Currency successfully updated ]', 'erp/finance/currency/', '', 'Qbs ', '2024-02-21 06:47:02'),
(2774, 'Currency Update', '[ Currency successfully updated ]', 'erp/finance/currency/', '', 'Qbs ', '2024-02-21 06:47:59'),
(2775, 'Currency Update', '[ Currency successfully updated ]', 'erp/finance/currency/', '', 'Qbs ', '2024-02-21 06:48:20'),
(2776, 'Currency Update', '[ Currency successfully updated ]', 'erp/finance/currency/', '', 'Qbs ', '2024-02-21 06:49:18'),
(2777, 'Currency Update', '[ Currency successfully updated ]', 'erp/finance/currency/', '', 'Qbs ', '2024-02-21 06:49:50'),
(2778, 'Currency Delete', '[ Currency successfully deleted ]', 'erp/finance/journalentry/', '', 'Qbs ', '2024-02-21 06:55:56'),
(2779, 'Currency Update', '[ Currency successfully updated ]', 'erp/finance/currency/', '', 'Qbs ', '2024-02-21 06:56:11'),
(2780, 'Currency Update', '[ Currency successfully updated ]', 'erp/finance/currency/', '', 'Qbs ', '2024-02-21 06:56:18'),
(2781, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/1', '', 'Qbs ', '2024-02-21 07:14:27'),
(2782, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/9', '', 'Qbs ', '2024-02-21 07:15:35'),
(2783, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/10', '', 'Qbs ', '2024-02-21 07:43:56'),
(2784, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/10', '', 'Qbs ', '2024-02-21 08:40:57'),
(2785, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/10', '', 'Qbs ', '2024-02-21 08:42:45'),
(2786, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/1', '', 'Qbs ', '2024-02-21 08:46:00'),
(2787, 'warehouse Deletion', '[ Warehouse successfully deleted ]', 'erp.sale.invoice.delete9', '{\"invoice_id\":\"9\",\"code\":\"INV-2024009\",\"cust_id\":\"37\",\"name\":\"admin\",\"invoice_date\":\"2024-02-21\",\"invoice_expiry\":\"2024-02-28\",\"shippingaddr_id\":\"0\",\"billingaddr_id\":\"\",\"transport_req\":\"0\",\"trans_charge\":\"0.00\",\"payment_terms\":\"fsdf\",\"terms_condition\":\"<p>fsf<\\/p>\",\"discount\":\"0.00\"}', 'Qbs ', '2024-02-21 08:49:30'),
(2788, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/10', '', 'Qbs ', '2024-02-21 08:50:24'),
(2789, 'Sale Invoice Update', '[ Sale Invoice successfully updated ]', 'erp/sale/invoiceview/10', '', 'Qbs ', '2024-02-21 08:52:54'),
(2790, 'warehouse Deletion', '[ Warehouse successfully deleted ]', 'erp.sale.invoice.delete8', '{\"invoice_id\":\"8\",\"code\":\"INV-2024008\",\"cust_id\":\"38\",\"name\":\"admin2\",\"invoice_date\":\"2024-02-14\",\"invoice_expiry\":\"2024-02-21\",\"shippingaddr_id\":\"0\",\"billingaddr_id\":\"\",\"transport_req\":\"1\",\"trans_charge\":\"100.00\",\"payment_terms\":\"1 day\",\"terms_condition\":\"<p>fsd<\\/p>\",\"discount\":\"0.00\"}', 'Qbs ', '2024-02-21 09:25:08'),
(2791, 'warehouse Deletion', '[ Warehouse successfully deleted ]', 'erp.sale.invoice.delete7', '{\"invoice_id\":\"7\",\"code\":\"INV-2024007\",\"cust_id\":\"45\",\"name\":\"john\",\"invoice_date\":\"2024-02-09\",\"invoice_expiry\":\"2024-02-16\",\"shippingaddr_id\":\"13\",\"billingaddr_id\":\"\",\"transport_req\":\"0\",\"trans_charge\":\"1.00\",\"payment_terms\":\"30 day\",\"terms_condition\":\"<p>sdffsf<\\/p>\",\"discount\":\"0.00\"}', 'Qbs ', '2024-02-21 09:25:18'),
(2792, 'Quotation Update', '[ Quotation successfully updated ]', 'erp/sale/quotationview/1', '', 'Qbs ', '2024-02-21 09:35:50'),
(2793, 'Quotation Accepted', '[ Quotation Accepted successfully ]', 'erp/sale/quotationview/1', '', 'Qbs ', '2024-02-21 09:36:07'),
(2794, 'Order Conversion', '[ Order Conversion successfully ]', 'http://192.168.29.9/erpcinew/public/erp/sales/order_view/9', '', 'Qbs ', '2024-02-21 09:47:12'),
(2795, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/10', '', 'Qbs ', '2024-02-21 09:54:57'),
(2796, 'warehouse Deletion', '[ Warehouse successfully deleted ]', 'erp.sale.invoice.delete3', '{\"invoice_id\":\"3\",\"code\":\"INV-2024003\",\"cust_id\":\"45\",\"name\":\"john\",\"invoice_date\":\"2024-02-06\",\"invoice_expiry\":\"2024-02-13\",\"shippingaddr_id\":\"13\",\"billingaddr_id\":\"2\",\"transport_req\":\"0\",\"trans_charge\":\"0.00\",\"payment_terms\":\"fds\",\"terms_condition\":\"<p>test<\\/p>\",\"discount\":\"0.00\"}', 'Qbs ', '2024-02-21 11:39:56'),
(2797, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/6', '', 'Qbs ', '2024-02-21 11:51:22'),
(2798, 'Invoice Deletion', '[ Invoice successfully deleted ]', 'erp.sale.invoice.delete6', '{\"invoice_id\":\"6\",\"code\":\"INV-2024006\",\"cust_id\":\"46\",\"name\":\"jacob\",\"invoice_date\":\"2024-02-09\",\"invoice_expiry\":\"2024-02-16\",\"shippingaddr_id\":\"14\",\"billingaddr_id\":\"\",\"transport_req\":\"0\",\"trans_charge\":\"1.00\",\"payment_terms\":\"dsf\",\"terms_condition\":\"<p>fsdf<\\/p>\",\"discount\":\"0.00\"}', 'Qbs ', '2024-02-21 11:52:04'),
(2799, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/5', '', 'Qbs ', '2024-02-21 11:52:33'),
(2800, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/5', '', 'Qbs ', '2024-02-21 11:52:46'),
(2801, 'Invoice Deletion', '[ Invoice successfully deleted ]', 'erp.sale.invoice.delete5', '{\"invoice_id\":\"5\",\"code\":\"INV-2024005\",\"cust_id\":\"37\",\"name\":\"admin\",\"invoice_date\":\"2024-02-06\",\"invoice_expiry\":\"2024-02-13\",\"shippingaddr_id\":\"3\",\"billingaddr_id\":\"\",\"transport_req\":\"0\",\"trans_charge\":\"10.00\",\"payment_terms\":\"test1\",\"terms_condition\":\"<p>sdf<\\/p>\",\"discount\":\"0.00\"}', 'Qbs ', '2024-02-21 11:53:06'),
(2802, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/4', '', 'Qbs ', '2024-02-21 11:56:12'),
(2803, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/4', '', 'Qbs ', '2024-02-21 11:56:39'),
(2804, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/10', '', 'Qbs ', '2024-02-21 13:13:09'),
(2805, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-22 04:57:15'),
(2806, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-22 04:59:04'),
(2807, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-22 06:49:32'),
(2808, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-22 06:49:58'),
(2809, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-22 06:50:14'),
(2810, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-22 07:29:54'),
(2811, 'Warehouse Insert', '[ Warehouse failed to create ]', 'erp/warehouse/warehouses', '', 'Qbs ', '2024-02-22 08:52:14'),
(2812, 'Planning Insert', '[ Planning failed to create ]', 'http://192.168.29.9/erpcinew/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-02-22 10:16:25'),
(2813, 'Planning Insert', '[ Planning successfully created ]', 'http://192.168.29.9/erpcinew/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-02-22 10:19:38'),
(2814, 'Planning Update', '[ Planning successfully updated ]', 'http://192.168.29.9/erpcinew/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-02-22 10:31:59'),
(2815, 'Planning Update', '[ Planning failed to update ]', 'http://192.168.29.9/erpcinew/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-02-22 10:32:10'),
(2816, 'Planning Update', '[ Planning failed to update ]', 'http://192.168.29.9/erpcinew/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-02-22 10:32:16'),
(2817, 'Planning Update', '[ Planning successfully updated ]', 'http://192.168.29.9/erpcinew/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-02-22 10:32:26'),
(2818, 'Planning Update', '[ Planning successfully updated ]', 'http://192.168.29.9/erpcinew/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-02-22 10:33:39'),
(2819, 'Planning Update', '[ Planning successfully updated ]', 'http://192.168.29.9/erpcinew/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-02-22 10:40:16'),
(2820, 'MRP Scheduling Insert', '[ MRP Scheduling successfully created ]', 'http://192.168.29.9/erpcinew/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-02-22 10:42:06'),
(2821, 'MRP Scheduling Insert', '[ MRP Scheduling successfully created ]', 'http://192.168.29.9/erpcinew/public/erp/mrp/planning-view/7', '', 'Qbs ', '2024-02-22 10:46:07'),
(2822, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/11', '', 'Qbs ', '2024-02-22 11:05:46'),
(2823, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/11', '', 'Qbs ', '2024-02-22 11:06:07'),
(2824, 'MRP Scheduling Insert', '[ MRP Scheduling successfully created ]', 'http://192.168.29.9/erpcinew/public/erp/mrp/planning-view/4', '', 'Qbs ', '2024-02-22 11:34:55'),
(2825, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/11', '', 'Qbs ', '2024-02-22 11:40:57'),
(2826, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-24 05:07:53'),
(2827, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-02-24 06:49:55'),
(2828, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/12', '', 'Qbs ', '2024-02-24 07:59:16'),
(2829, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/1', '', 'Qbs ', '2024-02-24 09:56:19'),
(2830, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/1', '', 'Qbs ', '2024-02-24 09:57:02'),
(2831, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/2', '', 'Qbs ', '2024-02-24 12:41:40'),
(2832, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/2', '', 'Qbs ', '2024-02-24 12:42:48'),
(2833, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-03-14 07:39:18'),
(2834, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-03-14 07:42:45'),
(2835, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-03-14 10:36:00'),
(2836, 'Requisition Insert', '[ Requisition successfully created ]', 'erp/procurement/requisitionview/7', '', 'Qbs ', '2024-03-14 10:47:35'),
(2837, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-03-14 11:28:42'),
(2838, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-03-14 11:29:01'),
(2839, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-03-14 12:50:43'),
(2840, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-03-14 12:53:18'),
(2841, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-03-15 04:40:49'),
(2842, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-03-26 11:31:06'),
(2843, 'Quotation Notification Insert', '[ Quotation Notification successfully created ]', 'http://192.168.29.3/ERP/public/erp/sales/quotations_view/1', '', 'Qbs ', '2024-03-26 11:47:52'),
(2844, 'Quotation Notification Insert', '[ Quotation Notification successfully created ]', 'http://192.168.29.3/ERP/public/erp/sales/quotations_view/1', '', 'Qbs ', '2024-03-26 12:10:18'),
(2845, 'Quotation Notification Delete', '[ Quotation Notification successfully deleted ]', 'erp/sale/quotationview/1', '', 'Qbs ', '2024-03-26 12:10:35'),
(2846, 'Quotation Notification Delete', '[ Quotation Notification successfully deleted ]', 'erp/sale/quotationview/1', '', 'Qbs ', '2024-03-26 12:12:08'),
(2847, 'Quotation Notification Insert', '[ Quotation Notification successfully created ]', 'http://192.168.29.3/ERP/public/erp/sales/quotations_view/1', '', 'Qbs ', '2024-03-26 12:12:30'),
(2848, 'Quotation Notification Delete', '[ Quotation Notification successfully deleted ]', 'erp/sale/quotationview/1', '', 'Qbs ', '2024-03-26 12:23:22'),
(2849, 'Quotation Notification Insert', '[ Quotation Notification successfully created ]', 'http://192.168.29.3/ERP/public/erp/sales/quotations_view/1', '', 'Qbs ', '2024-03-26 12:23:40'),
(2850, 'Quotation Notification Delete', '[ Quotation Notification successfully deleted ]', 'erp/sale/quotationview/1', '', 'Qbs ', '2024-03-26 12:27:09'),
(2851, 'Quotation Notification Insert', '[ Quotation Notification successfully created ]', 'http://192.168.29.3/ERP/public/erp/sales/quotations_view/1', '', 'Qbs ', '2024-03-26 12:27:27'),
(2852, 'Quotation Notification Update', '[ Quotation Notification successfully updated ]', 'erp/sale/quotationview/1', '', 'Qbs ', '2024-03-26 13:05:07'),
(2853, 'Quotation Notification Update', '[ Quotation Notification successfully updated ]', 'erp/sale/quotationview/1', '', 'Qbs ', '2024-03-26 13:07:17'),
(2854, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-03-26 13:36:05'),
(2855, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-03-26 13:36:05'),
(2856, 'Quotation Notification Insert', '[ Quotation Notification successfully created ]', 'http://192.168.29.3/ERP/public/erp/sales/quotations_view/1', '', 'Qbs ', '2024-03-26 13:37:30'),
(2857, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-03-27 05:15:10'),
(2858, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-03-27 05:31:01'),
(2859, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-03-27 12:59:41'),
(2860, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-03-28 06:11:36'),
(2861, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-03-28 07:31:21'),
(2862, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-03-28 07:32:00'),
(2863, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-03-28 07:36:35'),
(2864, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-03-28 09:17:24'),
(2865, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-03-28 09:17:37'),
(2866, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-03-28 09:19:28'),
(2867, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-03-28 09:19:41'),
(2868, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-03-28 09:20:40'),
(2869, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-03-28 09:29:09'),
(2870, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-04-06 05:17:49'),
(2871, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-04-13 08:42:56'),
(2872, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-04-13 08:46:03'),
(2873, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-04-13 08:46:13'),
(2874, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-04-13 08:46:36'),
(2875, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-04-27 04:54:59'),
(2876, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-04-27 04:56:59'),
(2877, 'Lead Insert', '[ Lead successfully created ]', 'http://192.168.29.3/ERP/public/erp/crm/lead-view/0', '', 'Qbs ', '2024-04-27 05:49:36'),
(2878, 'Task Insert', '[ Task failed to create ]', 'erp/crm/task', '', 'Qbs ', '2024-04-27 05:55:40'),
(2879, 'Customer Insert', '[ Customer successfully created ]', 'erp/crm/customerview/49', '', 'Qbs ', '2024-04-27 06:06:29'),
(2880, 'Customer Deletion', '[ Customer successfully deleted ]', 'erp.crm.customerdelete48', '{\"cust_id\":\"48\",\"name\":\"Production Thamizharasi\",\"position\":\"web developer\",\"address\":\"chennai\",\"city\":\"Thiruvallur\",\"state\":\"tamilnadu\",\"country\":\"India\",\"zip\":\"602024\",\"email\":\"tamil@gmail.com\",\"phone\":\"9578523633\",\"fax_num\":\"3253546\",\"office_num\":\"656554\",\"company\":\"qbs soft\",\"gst\":\"45666666666\",\"website\":\"\",\"description\":\"sfgarg\",\"remarks\":\"sadgasfg\",\"created_at\":\"1705748532\",\"created_by\":\"1\"}', 'Qbs ', '2024-04-27 06:07:12'),
(2881, 'Ticket Insert', '[ Ticket failed to create ]', 'erp/crm/tickets', '', 'Qbs ', '2024-04-27 06:37:39'),
(2882, 'Estimate Insert', '[ Estimate successfully created ]', 'erp/sale/estimateview/55', '', 'Qbs ', '2024-04-27 06:55:21'),
(2883, 'Quotation Insert', '[ Quotation successfully created ]', 'erp/sale/quotationview/2', '', 'Qbs ', '2024-04-27 06:57:09'),
(2884, 'Property Type Insert', '[ Property Type failed to create ]', 'erp/inventory/propertytype', '', 'Qbs ', '2024-04-27 07:20:19'),
(2885, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-04-27 07:34:30'),
(2886, 'RFQ Insert', '[ RFQ successfully created ]', 'erp/procurement/rfqview/3', '', 'Qbs ', '2024-04-27 07:44:30'),
(2887, 'GRN Update', '[ GRN successfully updated ]', 'erp/warehouse/grnview/1', '', 'Qbs ', '2024-04-27 07:51:00'),
(2888, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-04-29 05:19:00'),
(2889, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-04-29 05:20:58'),
(2890, 'Lead Insert', '[ Lead successfully created ]', 'http://192.168.29.3/ERP/public/erp/crm/lead-view/22', '', 'Qbs ', '2024-04-29 05:26:15'),
(2891, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-04-29 06:18:34'),
(2892, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-04-30 04:52:58'),
(2893, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 07:10:06'),
(2894, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 07:10:14'),
(2895, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 07:14:27'),
(2896, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 07:15:04'),
(2897, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 07:16:18'),
(2898, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 07:21:14'),
(2899, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 07:22:19'),
(2900, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 07:23:23'),
(2901, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 09:25:24'),
(2902, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 09:26:22'),
(2903, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 09:27:36'),
(2904, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 09:31:24'),
(2905, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 09:32:52'),
(2906, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 09:34:15'),
(2907, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 09:43:29'),
(2908, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 10:17:44'),
(2909, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 11:25:46'),
(2910, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 11:40:10'),
(2911, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 11:40:21'),
(2912, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 11:40:43'),
(2913, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-04-30 18:03:21'),
(2914, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-02 10:35:29'),
(2915, 'RFQ Send Batch', '[ RFQ Sent without suppliers ]', 'erp/procurement/rfqview/2', '', 'Qbs ', '2024-05-02 16:20:25'),
(2916, 'Supplier Supply List Insert', '[ Supplier Supply List failed to create ]', 'erp/supplier/supplier-view/6', '', 'Qbs ', '2024-05-02 16:21:28'),
(2917, 'Supplier Update', '[ Supplier successfully updated ]', 'erp/supplier/supplier-view/4', '', 'Qbs ', '2024-05-02 16:22:39'),
(2918, 'RFQ Supplier Insert', '[ RFQ Supplier failed to update ]', 'erp/procurement/rfqview/2', '', 'Qbs ', '2024-05-02 16:23:31'),
(2919, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-03 10:16:41'),
(2920, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-05-03 11:51:11'),
(2921, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-03 11:56:40'),
(2922, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-05-03 15:55:48'),
(2923, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-04 10:21:19'),
(2924, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-06 11:21:28'),
(2925, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-05-06 17:57:30'),
(2926, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-06 17:58:00'),
(2927, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-07 10:31:51'),
(2928, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://localhost/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-05-07 12:12:46'),
(2929, 'Department Insert', '[ Department successfully created ]', 'erp/hr/departments/', '', 'Qbs ', '2024-05-07 12:17:56'),
(2930, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-08 10:08:47'),
(2931, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-08 15:24:40'),
(2932, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-08 15:25:09'),
(2933, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-09 10:14:24'),
(2934, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-10 10:41:19'),
(2935, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-11 10:09:12'),
(2936, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-13 10:05:07'),
(2937, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-14 10:04:52'),
(2938, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-05-14 15:29:35'),
(2939, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-14 15:29:43'),
(2940, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-15 10:09:39'),
(2941, 'Mail Settings Update', '[ Mail Settings successfully updated ]', '', '', 'Qbs ', '2024-05-15 15:50:58'),
(2942, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-16 10:21:07'),
(2943, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-16 16:02:43'),
(2944, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-16 17:53:48'),
(2945, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-17 10:20:03'),
(2946, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-17 18:49:08'),
(2947, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-18 10:08:46'),
(2948, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-20 10:07:15'),
(2949, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-20 10:07:16'),
(2950, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-21 10:12:32'),
(2951, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-23 10:10:52'),
(2952, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-24 10:29:48'),
(2953, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-25 10:07:06'),
(2954, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-27 10:09:08'),
(2955, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-28 10:08:48'),
(2956, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-28 10:51:20'),
(2957, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-29 10:09:30'),
(2958, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-30 10:11:48'),
(2959, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-05-31 10:24:52'),
(2960, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-01 10:21:24'),
(2961, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-03 10:35:40'),
(2962, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-04 10:12:55'),
(2963, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-04 17:51:03'),
(2964, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-05 10:08:07'),
(2965, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-05 15:29:07'),
(2966, 'Quotation Accepted', '[ Quotation Accepted successfully ]', 'erp/sale/quotationview/2', '', 'Qbs ', '2024-06-05 18:23:07'),
(2967, 'Order Conversion', '[ Order Conversion successfully ]', 'http://192.168.29.138/Erp/public/erp/sales/order_view/10', '', 'Qbs ', '2024-06-05 18:23:28'),
(2968, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-06 10:10:06'),
(2969, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-07 10:19:12'),
(2970, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-07 11:10:23'),
(2971, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-07 16:03:10'),
(2972, 'Account Group Insert', '[ Account Group successfully created ]', 'erp/finance/accountgroup/', '', 'Qbs ', '2024-06-07 16:57:13'),
(2973, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-07 18:05:56'),
(2974, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-08 10:08:15'),
(2975, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/1', '', 'Qbs ', '2024-06-08 15:22:20'),
(2976, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/2', '', 'Qbs ', '2024-06-08 15:24:00'),
(2977, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-10 10:21:10'),
(2978, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/3', '', 'Qbs ', '2024-06-10 10:39:26'),
(2979, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-10 12:04:04');
INSERT INTO `erp_log` (`log_id`, `title`, `log_text`, `ref_link`, `additional_info`, `done_by`, `created_at`) VALUES
(2980, 'User Update', '[ User successfully updated ]', 'http://192.168.29.138/Erp/public/erp/setting/user-edit/1', '', 'Qbs ', '2024-06-10 12:24:54'),
(2981, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/7', '', 'Qbs ', '2024-06-10 12:57:15'),
(2982, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/7', '', 'Qbs ', '2024-06-10 12:58:07'),
(2983, 'Invoice Deletion', '[ Invoice successfully deleted ]', 'erp.sale.invoice.delete7', '{\"invoice_id\":\"7\",\"code\":\"INV-2024004\",\"cust_id\":\"37\",\"name\":\"admin\",\"invoice_date\":\"2024-06-10\",\"invoice_expiry\":\"2024-06-17\",\"shippingaddr_id\":\"0\",\"billingaddr_id\":\"\",\"transport_req\":\"0\",\"trans_charge\":\"0.00\",\"payment_terms\":\"expense add\",\"terms_condition\":\"\",\"discount\":\"0.00\"}', 'Qbs ', '2024-06-10 13:13:17'),
(2984, 'Invoice Deletion', '[ Invoice failed to delete ]', '3', '', 'Qbs ', '2024-06-10 13:13:21'),
(2985, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/8', '', 'Qbs ', '2024-06-10 13:14:05'),
(2986, 'Invoice Deletion', '[ Invoice failed to delete ]', '8', '', 'Qbs ', '2024-06-10 13:17:25'),
(2987, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/9', '', 'Qbs ', '2024-06-10 13:18:05'),
(2988, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/10', '', 'Qbs ', '2024-06-10 13:23:43'),
(2989, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/11', '', 'Qbs ', '2024-06-10 13:25:44'),
(2990, 'Invoice Deletion', '[ Invoice successfully deleted ]', 'erp.sale.invoice.delete2', '{\"invoice_id\":\"2\",\"code\":\"INV-2024002\",\"cust_id\":\"45\",\"name\":\"john\",\"invoice_date\":\"2024-02-24\",\"invoice_expiry\":\"2024-03-02\",\"shippingaddr_id\":\"0\",\"billingaddr_id\":\"\",\"transport_req\":\"0\",\"trans_charge\":\"0.00\",\"payment_terms\":\"fds\",\"terms_condition\":\"<p>fdsf<\\/p>\",\"discount\":\"0.00\"}', 'Qbs ', '2024-06-10 14:22:29'),
(2991, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/12', '', 'Qbs ', '2024-06-10 14:23:08'),
(2992, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/13', '', 'Qbs ', '2024-06-10 14:35:16'),
(2993, 'Invoice Deletion', '[ Invoice failed to delete ]', '13', '', 'Qbs ', '2024-06-10 15:59:56'),
(2994, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/14', '', 'Qbs ', '2024-06-10 16:00:17'),
(2995, 'Invoice Deletion', '[ Invoice failed to delete ]', '14', '', 'Qbs ', '2024-06-10 16:03:59'),
(2996, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/15', '', 'Qbs ', '2024-06-10 16:05:35'),
(2997, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/16', '', 'Qbs ', '2024-06-10 16:14:07'),
(2998, 'Invoice Deletion', '[ Invoice failed to delete ]', '16', '', 'Qbs ', '2024-06-10 16:44:22'),
(2999, 'Invoice Deletion', '[ Invoice failed to delete ]', '15', '', 'Qbs ', '2024-06-10 16:44:26'),
(3000, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/17', '', 'Qbs ', '2024-06-10 16:50:20'),
(3001, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/18', '', 'Qbs ', '2024-06-10 17:00:30'),
(3002, 'Invoice Deletion', '[ Invoice failed to delete ]', '18', '', 'Qbs ', '2024-06-10 17:00:40'),
(3003, 'Invoice Deletion', '[ Invoice failed to delete ]', '17', '', 'Qbs ', '2024-06-10 17:25:39'),
(3004, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/19', '', 'Qbs ', '2024-06-10 17:26:38'),
(3005, 'Sale Payment Insert', '[ Sale Payment failed to create ]', 'erp/sale/invoice_view/19', '', 'Qbs ', '2024-06-10 17:53:47'),
(3006, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/19', '', 'Qbs ', '2024-06-10 18:04:34'),
(3007, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/19', '', 'Qbs ', '2024-06-10 18:10:45'),
(3008, 'Sale Invoice Notification Insert', '[ Sale Invoice Notification successfully created ]', 'erp/sale/invoiceview/19', '', 'Qbs ', '2024-06-10 18:11:27'),
(3009, 'Sale Invoice Notification Insert', '[ Sale Invoice Notification successfully created ]', 'erp/sale/invoiceview/19', '', 'Qbs ', '2024-06-10 18:15:05'),
(3010, 'Sale Payment Delete', '[ Sale Payment failed to delete ]', 'erp/sale/manage_view/19', '', 'Qbs ', '2024-06-10 19:08:49'),
(3011, 'Sale Payment Update', '[ Sale Payment failed to update ]', 'erp/sale/manage_view/19', '', 'Qbs ', '2024-06-10 19:09:05'),
(3012, 'Sale Payment Update', '[ Sale Payment successfully updated ]', 'erp/sale/manage_view/19', '', 'Qbs ', '2024-06-10 19:15:57'),
(3013, 'Sale Payment Delete', '[ Sale Payment successfully deleted ]', 'erp/sale/manage_view/19', '', 'Qbs ', '2024-06-10 19:19:02'),
(3014, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/19', '', 'Qbs ', '2024-06-10 19:19:51'),
(3015, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/20', '', 'Qbs ', '2024-06-10 19:21:52'),
(3016, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/20', '', 'Qbs ', '2024-06-10 19:22:11'),
(3017, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/20', '', 'Qbs ', '2024-06-10 19:22:31'),
(3018, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-11 10:19:39'),
(3019, 'Credit Note Insert', '[ Credit Note successfully created ]', 'http://192.168.29.138/Erp/public/erp/sales/credit_notes', '', 'Qbs ', '2024-06-11 11:56:45'),
(3020, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/21', '', 'Qbs ', '2024-06-11 12:32:07'),
(3021, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/21', '', 'Qbs ', '2024-06-11 12:32:42'),
(3022, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-12 10:20:24'),
(3023, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-12 16:50:04'),
(3024, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-13 10:33:07'),
(3025, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-13 10:45:15'),
(3026, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-13 12:56:14'),
(3027, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-14 10:22:00'),
(3028, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.138/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-06-14 18:55:09'),
(3029, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-15 10:39:36'),
(3030, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-06-15 10:40:51'),
(3031, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-15 10:40:59'),
(3032, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-06-15 10:41:05'),
(3033, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-15 10:41:12'),
(3034, 'Brand Insert', '[ Brand successfully created ]', 'erp/inventory/brands', '', 'Qbs ', '2024-06-15 10:43:20'),
(3035, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-17 10:23:40'),
(3036, 'Role Insert', '[ Role successfully created ]', 'http://192.168.29.138/Erp/public/erp/setting/role-view/0', '', 'Qbs ', '2024-06-17 14:05:27'),
(3037, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-06-17 15:41:26'),
(3038, 'Login', '[ User successfully logged in ]', '', '', 'Udhaya', '2024-06-17 15:42:02'),
(3039, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-06-25 10:45:24'),
(3040, 'Credit Note Insert', '[ Credit Note successfully created ]', 'http://192.168.29.138/Erp/public/erp/sales/credit_notes', '', 'Qbs ', '2024-06-25 10:58:38'),
(3041, 'Credit Note Insert', '[ Credit Note successfully created ]', 'http://192.168.29.138/Erp/public/erp/sales/credit_notes', '', 'Qbs ', '2024-06-25 12:40:35'),
(3042, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-10-09 10:11:43'),
(3043, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-10-09 10:21:38'),
(3044, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-10-10 12:46:42'),
(3045, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-10-18 11:49:47'),
(3046, 'RFQ Send Batch', '[ RFQ Send Batch sucessfully created ]', 'erp/procurement/rfqview/2', '', 'Qbs ', '2024-10-18 11:59:49'),
(3047, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-01 19:11:38'),
(3048, 'Estimate Insert', '[ Estimate successfully created ]', 'erp/sale/estimateview/56', '', 'Qbs ', '2024-11-01 19:23:05'),
(3049, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-02 10:14:05'),
(3050, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/22', '', 'Qbs ', '2024-11-02 17:32:06'),
(3051, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-07 17:53:23'),
(3052, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-09 15:20:34'),
(3053, 'Customer Insert', '[ Customer successfully created ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/50', '', 'Qbs ', '2024-11-09 16:48:31'),
(3054, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-09 18:44:58'),
(3055, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-11 10:46:06'),
(3056, 'Lead Update', '[ Lead successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-view/1', '', 'Qbs ', '2024-11-11 15:08:47'),
(3057, 'Lead Update', '[ Lead successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-view/1', '', 'Qbs ', '2024-11-11 15:09:10'),
(3058, 'Lead Update', '[ Lead successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-view/1', '', 'Qbs ', '2024-11-11 15:13:13'),
(3059, 'Lead Update', '[ Lead failed to update ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-view/1', '', 'Qbs ', '2024-11-11 15:13:40'),
(3060, 'Lead Update', '[ Lead successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-view/1', '', 'Qbs ', '2024-11-11 15:13:48'),
(3061, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-11 16:07:52'),
(3062, 'Lead Update', '[ Lead successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-view/5', '', 'Qbs ', '2024-11-11 16:55:18'),
(3063, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-11 19:41:47'),
(3064, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-13 10:10:18'),
(3065, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-13 10:35:22'),
(3066, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-13 10:45:45'),
(3067, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-13 11:53:27'),
(3068, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-13 11:53:34'),
(3069, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-13 11:53:52'),
(3070, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-13 11:55:47'),
(3071, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-13 12:34:24'),
(3072, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-11-13 16:31:24'),
(3073, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-11-13 16:31:26'),
(3074, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-11-13 16:31:29'),
(3075, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-11-13 16:31:32'),
(3076, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-11-13 16:31:34'),
(3077, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-11-13 16:31:39'),
(3078, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-11-13 16:31:44'),
(3079, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-11-13 16:31:47'),
(3080, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-11-13 16:31:50'),
(3081, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-11-13 16:31:53'),
(3082, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-11-13 16:31:57'),
(3083, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-11-13 16:32:00'),
(3084, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-11-13 16:32:03'),
(3085, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-11-13 16:32:07'),
(3086, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-11-13 16:32:09'),
(3087, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-11-13 16:32:12'),
(3088, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-13 18:46:49'),
(3089, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-14 10:19:55'),
(3090, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-14 10:56:53'),
(3091, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-14 11:32:15'),
(3092, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-15 10:19:52'),
(3093, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-15 10:25:30'),
(3094, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-15 12:53:18'),
(3095, 'Task Update', '[ Task successfully updated ]', 'erp/crm/task', '', 'Qbs ', '2024-11-15 15:41:06'),
(3096, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-15 17:41:54'),
(3097, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-15 18:32:30'),
(3098, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-15 18:32:30'),
(3099, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-15 19:28:11'),
(3100, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-16 10:08:39'),
(3101, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-16 11:11:06'),
(3102, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-16 14:20:52'),
(3103, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-18 10:31:17'),
(3104, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-18 10:47:54'),
(3105, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/23', '', 'Qbs ', '2024-11-18 13:29:25'),
(3106, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-19 10:55:29'),
(3107, 'Generating Backup', '[Datebase Backup Created Successfully.]', 'http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view', '', 'Qbs ', '2024-11-19 12:45:21'),
(3108, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-19 15:36:25'),
(3109, 'Contract Task Add', '[ Added Contract Task Added Successfully ]', '', '', 'Qbs ', '2024-11-19 17:42:23'),
(3110, 'Contract Task Add', '[ Added Contract Task Added Successfully ]', '', '', 'Qbs ', '2024-11-19 17:43:16'),
(3111, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-20 10:39:19'),
(3112, 'Contract Add Content', '[ Content Added Successfully ]', '', '', 'Qbs ', '2024-11-20 12:07:40'),
(3113, 'Contract Comment', '[ Comment added in  contract successfully ]', '', '', 'Qbs ', '2024-11-20 12:08:14'),
(3114, 'Contract Comment', '[ Contract Comment Updated Successfully ]', '', '', 'Qbs ', '2024-11-20 12:08:21'),
(3115, 'Contract Comment', '[ Contract Comment Deleted ]', '', '', 'Qbs ', '2024-11-20 12:08:23'),
(3116, 'Contract Renewal Delete', '[ Contract Renewal Delete successfully ]', '', '', 'Qbs ', '2024-11-20 12:08:42'),
(3117, 'Contract Renewal', '[ Updated Successfully ]', '', '', 'Qbs ', '2024-11-20 12:08:49'),
(3118, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-20 12:15:03'),
(3119, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-21 10:45:46'),
(3120, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-22 10:38:16'),
(3121, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-22 15:36:53'),
(3122, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-11-22 17:39:08'),
(3123, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-22 17:42:00'),
(3124, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-22 17:42:40'),
(3125, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-22 18:16:16'),
(3126, 'Mail Settings Update', '[ Mail Settings successfully updated ]', '', '', 'Qbs ', '2024-11-22 18:33:38'),
(3127, 'Mail Settings Update', '[ Mail Settings successfully updated ]', '', '', 'Qbs ', '2024-11-22 18:34:18'),
(3128, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-22 18:34:20'),
(3129, 'Mail Settings Update', '[ Mail Settings successfully updated ]', '', '', 'Qbs ', '2024-11-22 18:40:41'),
(3130, 'Mail Settings Update', '[ Mail Settings successfully updated ]', '', '', 'Qbs ', '2024-11-22 18:41:30'),
(3131, 'Mail Settings Update', '[ Mail Settings successfully updated ]', '', '', 'Qbs ', '2024-11-22 18:43:40'),
(3132, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-23 10:51:08'),
(3133, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-25 10:45:04'),
(3134, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-11-25 11:49:15'),
(3135, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-25 11:49:18'),
(3136, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-25 11:53:07'),
(3137, 'Customer Contact Insert', '[ Customer Contact successfully created ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-25 12:05:55'),
(3138, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-25 16:58:18'),
(3139, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-11-25 16:58:22'),
(3140, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-25 16:58:28'),
(3141, 'Customer Contact Update', '[ Customer Contact failed to update ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-25 17:47:48'),
(3142, 'Customer Contact Update', '[ Customer Contact failed to update ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-25 17:48:43'),
(3143, 'Customer Contact Update', '[ Customer Contact failed to update ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-25 17:57:52'),
(3144, 'Customer Contact Update', '[ Customer Contact failed to update ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-25 17:59:01'),
(3145, 'Customer Contact Update', '[ Customer Contact failed to update ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-25 18:00:25'),
(3146, 'Customer Contact Update', '[ Customer Contact failed to update ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-25 18:37:26'),
(3147, 'Customer Contact Update', '[ Customer Contact failed to update ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-25 18:39:17'),
(3148, 'Customer Contact Update', '[ Customer Contact failed to update ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-25 18:42:31'),
(3149, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-25 18:52:55'),
(3150, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-25 18:56:50'),
(3151, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-25 19:03:42'),
(3152, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-26 10:32:48'),
(3153, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-11-26 10:34:31'),
(3154, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-26 10:50:05'),
(3155, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-26 12:39:50'),
(3156, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-26 14:14:35'),
(3157, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-26 14:15:29'),
(3158, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-11-26 14:16:02'),
(3159, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-26 15:06:52'),
(3160, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-26 15:07:45'),
(3161, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-26 15:07:54'),
(3162, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-26 15:09:43'),
(3163, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-26 15:11:53'),
(3164, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-11-26 15:17:13'),
(3165, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-27 14:13:10'),
(3166, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-27 17:45:15'),
(3167, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-28 11:31:20'),
(3168, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-28 11:35:34'),
(3169, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-11-28 11:35:43'),
(3170, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-28 15:48:41'),
(3171, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-28 16:23:50'),
(3172, 'Logout', '[ Client successfully logout ]', '', '', 'Qbs ', '2024-11-28 18:27:44'),
(3173, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-28 18:42:51'),
(3174, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-28 18:43:30'),
(3175, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-28 18:44:20'),
(3176, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-28 18:51:01'),
(3177, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-28 18:51:14'),
(3178, 'Customer Contact Insert', '[ Customer Contact successfully created ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-28 19:21:16'),
(3179, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-29 10:20:15'),
(3180, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-29 10:38:16'),
(3181, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 11:22:54'),
(3182, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 11:25:24'),
(3183, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 11:26:16'),
(3184, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 11:32:41'),
(3185, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 11:34:19'),
(3186, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 11:40:50'),
(3187, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 11:45:44'),
(3188, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 11:46:19'),
(3189, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 11:50:21'),
(3190, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 11:50:57'),
(3191, 'Customer Deletion', '[ Customer successfully deleted ]', 'erp.crm.customercontactdelete37', '{\"contact_id\":\"13\",\"firstname\":\"test\",\"lastname\":\"jac\",\"email\":\"jaccaj@gmail.com\",\"phone\":\"1234567890\",\"active\":\"1\",\"position\":\"tet34\",\"cust_id\":\"37\",\"created_at\":\"1707470788\",\"created_by\":\"1\",\"password\":\"$2y$10$IZ6MRM3glk\\/qtAh.J6BMwebMBL488DL05.qz4t9s9WaTmufIexMqm\",\"primary_contact\":\"1\",\"profile_image\":\"\",\"password_updated_at\":null}', 'Qbs ', '2024-11-29 11:53:17'),
(3192, 'Customer Contact Insert', '[ Customer Contact successfully created ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 11:54:50'),
(3193, 'Customer Contact Insert', '[ Customer Contact successfully created ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 11:56:38'),
(3194, 'Customer Contact Insert', '[ Customer Contact successfully created ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 11:57:13'),
(3195, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 11:57:56'),
(3196, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 12:05:03'),
(3197, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 12:12:53'),
(3198, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 12:15:11'),
(3199, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 12:16:23'),
(3200, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 12:57:47'),
(3201, 'Contract Update', '[ Can\'t Update Contract ]', '', '', 'Qbs ', '2024-11-29 13:22:40'),
(3202, 'Project Phase Insert', '[ Project Phase failed to create ]', 'http://192.168.29.3/Erp/public/erp/project/projects-view/23', '', 'Qbs ', '2024-11-29 15:46:12'),
(3203, 'Project Phase Insert', '[ Project Phase failed to create ]', 'http://192.168.29.3/Erp/public/erp/project/projects-view/23', '', 'Qbs ', '2024-11-29 15:46:28'),
(3204, 'Customer Contact Insert', '[ Customer Contact failed to create ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 17:01:08'),
(3205, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 17:17:59'),
(3206, 'Customer Deletion', '[ Customer successfully deleted ]', 'erp.crm.customercontactdelete37', '{\"contact_id\":\"17\",\"firstname\":\"sunil\",\"lastname\":\"kumar\",\"email\":\"sunil@gmail.com\",\"phone\":\"9554567891\",\"active\":\"1\",\"position\":\"customer\",\"cust_id\":\"37\",\"created_at\":\"1732861490\",\"created_by\":\"1\",\"password\":\"\",\"primary_contact\":\"1\",\"profile_image\":\"OIP.jpeg\",\"permission\":\"\",\"password_updated_at\":null}', 'Qbs ', '2024-11-29 17:25:00'),
(3207, 'Customer Contact Insert', '[ Customer Contact successfully created ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 17:25:42'),
(3208, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 17:31:53'),
(3209, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 17:32:30'),
(3210, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-11-29 17:32:49'),
(3211, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 17:33:47'),
(3212, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-11-29 17:34:30'),
(3213, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 17:39:47'),
(3214, 'Sale Payment Delete', '[ Sale Payment successfully deleted ]', 'erp/sale/manage_view/1', '', 'Qbs ', '2024-11-29 17:42:06'),
(3215, 'Customer Contact Update', '[ Customer Contact failed to update ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 17:42:47'),
(3216, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/1', '', 'Qbs ', '2024-11-29 17:42:47'),
(3217, 'Sale Payment Insert', '[ Sale Payment successfully created ]', 'erp/sale/invoice_view/1', '', 'Qbs ', '2024-11-29 17:42:48'),
(3218, 'Customer Contact Update', '[ Customer Contact failed to update ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 17:45:47'),
(3219, 'Customer Contact Update', '[ Customer Contact failed to update ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 17:47:31'),
(3220, 'Customer Contact Update', '[ Customer Contact failed to update ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 18:02:38'),
(3221, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 18:04:29'),
(3222, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 18:05:20'),
(3223, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 18:25:34'),
(3224, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 18:29:04'),
(3225, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 18:48:27'),
(3226, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 18:54:17'),
(3227, 'Customer Contact Update', '[ Customer Contact failed to update ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 18:54:38'),
(3228, 'Customer Contact Update', '[ Customer Contact failed to update ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 19:00:20'),
(3229, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-11-29 19:00:37'),
(3230, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-02 11:08:19'),
(3231, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-12-02 11:34:02'),
(3232, 'Logout', '[ Client successfully logout ]', '', '', 'Qbs ', '2024-12-02 11:41:59'),
(3233, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-02 11:56:09'),
(3234, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-12-02 11:56:29'),
(3235, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-12-02 12:06:05'),
(3236, 'Logout', '[ Client successfully logout ]', '', '', 'Qbs ', '2024-12-02 12:06:33'),
(3237, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-02 12:30:34'),
(3238, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-12-02 12:30:59'),
(3239, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-02 18:10:19'),
(3240, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-03 10:47:03'),
(3241, 'Logout', '[ Client successfully logout ]', '', '', 'Qbs ', '2024-12-03 13:05:52'),
(3242, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-03 13:10:47'),
(3243, 'Requisition Status Update', '[ Requisition Status failed to update ]', 'erp/procurement/requisitionview/2', '', 'Qbs ', '2024-12-03 14:45:32'),
(3244, 'GRN Update', '[ GRN successfully updated ]', 'erp/warehouse/grnview/1', '', 'Qbs ', '2024-12-03 14:53:13'),
(3245, 'Warehouse GRN Update', '[ Warehouse GRN Updated Successfully ]', '', '', 'Qbs ', '2024-12-03 14:53:13'),
(3246, 'GRN Update', '[ GRN successfully updated ]', 'erp/warehouse/grnview/55848', '', 'Qbs ', '2024-12-03 14:54:30'),
(3247, 'Warehouse GRN Update', '[ Warehouse GRN Updated Successfully ]', '', '', 'Qbs ', '2024-12-03 14:54:30'),
(3248, 'GRN Update', '[ GRN successfully updated ]', 'erp/warehouse/grnview/55848', '', 'Qbs ', '2024-12-03 14:55:42'),
(3249, 'Warehouse GRN Update', '[ Warehouse GRN Updated Successfully ]', '', '', 'Qbs ', '2024-12-03 14:55:42'),
(3250, 'Ticket Update', '[ Ticket failed to update ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-03 16:23:01'),
(3251, 'Ticket Update', '[ Ticket successfully updated ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-03 16:25:02'),
(3252, 'Tickets Delete', '[ Tickets successfully deleted ]', 'http://192.168.29.137/Erp/public/erp/crm/marketing', '', 'Qbs ', '2024-12-03 16:39:19'),
(3253, 'Tickets Delete', '[ Tickets successfully deleted ]', 'http://192.168.29.137/Erp/public/erp/crm/marketing', '', 'Qbs ', '2024-12-03 16:39:24'),
(3254, 'Ticket Insert', '[ Ticket failed to create ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-03 16:44:36'),
(3255, 'Ticket Insert', '[ Ticket failed to create ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-03 16:45:06'),
(3256, 'Ticket Insert', '[ Ticket failed to create ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-03 16:49:31'),
(3257, 'Ticket Insert', '[ Ticket failed to create ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-03 16:51:59'),
(3258, 'Ticket Insert', '[ Ticket failed to create ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-03 16:53:45'),
(3259, 'Ticket Insert', '[ Ticket failed to create ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-03 16:57:13'),
(3260, 'Ticket Insert', '[ Ticket failed to create ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-03 16:58:19'),
(3261, 'Ticket Insert', '[ Ticket failed to create ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-03 16:59:41'),
(3262, 'Ticket Insert', '[ Ticket failed to create ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-03 17:24:57'),
(3263, 'Ticket Insert', '[ Ticket successfully created ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-03 17:31:50'),
(3264, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.137/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-12-03 19:00:46'),
(3265, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.137/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-12-03 19:05:05'),
(3266, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.137/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-12-03 19:05:16'),
(3267, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.137/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-12-03 19:06:35'),
(3268, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.137/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-12-03 19:09:04'),
(3269, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.137/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-12-03 19:09:12'),
(3270, 'Customer Contact Update', '[ Customer Contact successfully updated ]', 'http://192.168.29.137/Erp/public/erp/crm/lead-customer-view/37', '', 'Qbs ', '2024-12-03 19:14:24'),
(3271, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-04 10:56:38'),
(3272, 'Ticket Insert', '[ Ticket successfully created ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-04 14:52:28'),
(3273, 'Ticket Update', '[ Ticket failed to update ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-04 14:52:57'),
(3274, 'Ticket Update', '[ Ticket failed to update ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-04 14:53:17'),
(3275, 'Ticket Update', '[ Ticket failed to update ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-04 14:55:31'),
(3276, 'Ticket Update', '[ Ticket failed to update ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-04 14:55:52'),
(3277, 'Ticket Update', '[ Ticket failed to update ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-04 14:56:06'),
(3278, 'Ticket Update', '[ Ticket successfully updated ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-04 14:56:49'),
(3279, 'Ticket Update', '[ Ticket successfully updated ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-04 14:57:05'),
(3280, 'Ticket Update', '[ Ticket successfully updated ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-04 14:57:17'),
(3281, 'Ticket Update', '[ Ticket successfully updated ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-04 15:11:27'),
(3282, 'Ticket Update', '[ Ticket successfully updated ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-04 18:40:38'),
(3283, 'Ticket Insert', '[ Ticket successfully created ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-04 19:05:30'),
(3284, 'Ticket Insert', '[ Ticket successfully created ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-04 19:10:53'),
(3285, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-05 12:44:34'),
(3286, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-05 12:48:27'),
(3287, 'Logout', '[ Client successfully logout ]', '', '', 'Qbs ', '2024-12-05 14:10:36'),
(3288, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-05 15:19:31'),
(3289, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-06 10:30:20'),
(3290, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-06 10:35:51'),
(3291, 'Ticket Insert', '[ Ticket successfully created ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-06 11:36:31'),
(3292, 'Ticket Update', '[ Ticket successfully updated ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-06 11:41:32'),
(3293, 'Ticket Insert', '[ Ticket successfully created ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-06 11:46:21'),
(3294, 'Ticket Insert', '[ Ticket successfully created ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-06 11:58:42'),
(3295, 'Tickets Delete', '[ Tickets successfully deleted ]', 'http://192.168.29.137/Erp/public/erp/crm/marketing', '', 'Qbs ', '2024-12-06 12:00:00'),
(3296, 'Tickets Delete', '[ Tickets successfully deleted ]', 'http://192.168.29.137/Erp/public/erp/crm/marketing', '', 'Qbs ', '2024-12-06 12:00:06'),
(3297, 'Ticket Insert', '[ Ticket successfully created ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-06 12:00:58'),
(3298, 'Ticket Insert', '[ Ticket successfully created ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-06 12:03:39'),
(3299, 'Tickets Delete', '[ Tickets successfully deleted ]', 'http://192.168.29.137/Erp/public/erp/crm/marketing', '', 'Qbs ', '2024-12-06 12:04:19'),
(3300, 'Tickets Delete', '[ Tickets successfully deleted ]', 'http://192.168.29.137/Erp/public/erp/crm/marketing', '', 'Qbs ', '2024-12-06 12:04:24'),
(3301, 'Tickets Delete', '[ Tickets successfully deleted ]', 'http://192.168.29.137/Erp/public/erp/crm/marketing', '', 'Qbs ', '2024-12-06 12:04:30'),
(3302, 'Tickets Delete', '[ Tickets successfully deleted ]', 'http://192.168.29.137/Erp/public/erp/crm/marketing', '', 'Qbs ', '2024-12-06 12:04:36'),
(3303, 'Ticket Insert', '[ Ticket successfully created ]', 'erp/crm/tickets', '', 'Qbs ', '2024-12-06 12:05:23'),
(3304, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-06 13:11:43'),
(3305, 'Project Phase Insert', '[ Project Phase failed to create ]', 'http://192.168.29.137/Erp/public/erp/project/projects-view/23', '', 'Qbs ', '2024-12-06 18:12:55'),
(3306, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-07 10:32:10'),
(3307, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-07 12:01:50'),
(3308, 'Planning Insert', '[ Planning successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-07 12:49:00'),
(3309, 'MRP Scheduling Insert', '[ MRP Scheduling successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/12', '', 'Qbs ', '2024-12-07 12:50:51'),
(3310, 'MRP Scheduling Insert', '[ MRP Scheduling successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/12', '', 'Qbs ', '2024-12-07 12:51:32'),
(3311, 'Planning Update', '[ Planning failed to update ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-07 13:19:01'),
(3312, 'Scheduling Delete', '[ Scheduling failed to delete ]', 'http://192.168.29.137/Erp/public/erp/service/service-view/1', '', 'Qbs ', '2024-12-07 14:35:20'),
(3313, 'Scheduling Delete', '[ Scheduling failed to delete ]', 'http://192.168.29.137/Erp/public/erp/service/service-view/1', '', 'Qbs ', '2024-12-07 14:36:09'),
(3314, ' Finished Good Insert', '[ Finished Good successfully created ]', '', '', 'Qbs ', '2024-12-07 15:38:06'),
(3315, 'MRP Scheduling Insert', '[ MRP Scheduling successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-07 16:24:31'),
(3316, 'MRP Scheduling Insert', '[ MRP Scheduling successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/2', '', 'Qbs ', '2024-12-07 16:52:08'),
(3317, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-09 10:05:07'),
(3318, 'MRP Scheduling Insert', '[ MRP Scheduling successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 10:11:54'),
(3319, 'MRP Scheduling Insert', '[ MRP Scheduling successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 10:13:26'),
(3320, 'MRP Scheduling Insert', '[ MRP Scheduling successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 10:14:14'),
(3321, 'MRP Scheduling Insert', '[ MRP Scheduling successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 10:17:42'),
(3322, 'MRP Scheduling Insert', '[ MRP Scheduling successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 10:17:50'),
(3323, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-09 10:18:01'),
(3324, 'MRP Scheduling Insert', '[ MRP Scheduling successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 10:22:24'),
(3325, 'MRP Scheduling Insert', '[ MRP Scheduling successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 10:23:39'),
(3326, 'MRP Scheduling Insert', '[ MRP Scheduling successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 10:23:51'),
(3327, 'Estimate Insert', '[ Estimate successfully created ]', 'erp/sale/estimateview/57', '', 'Qbs ', '2024-12-09 15:44:30'),
(3328, 'Estimate Update', '[ Estimate successfully updated ]', 'erp/sale/estimateview/41', '', 'Qbs ', '2024-12-09 16:01:22'),
(3329, 'Sale Invoice Insert', '[ Sale Invoice successfully created ]', 'erp/sale/invoiceview/24', '', 'Qbs ', '2024-12-09 16:11:05'),
(3330, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 17:51:09'),
(3331, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 17:51:32'),
(3332, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 17:52:27'),
(3333, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 17:52:53'),
(3334, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 17:56:28'),
(3335, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 18:00:55'),
(3336, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 18:01:43'),
(3337, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 18:02:59'),
(3338, 'MRP Scheduling Insert', '[ BOM failed to create ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-09 18:07:34'),
(3339, 'MRP Scheduling Insert', '[ BOM failed to create ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-09 18:09:43'),
(3340, 'MRP Scheduling Insert', '[ BOM failed to create ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-09 18:10:14'),
(3341, 'MRP Scheduling Insert', '[ BOM failed to create ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-09 18:11:32'),
(3342, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 18:13:04'),
(3343, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 18:15:11'),
(3344, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 18:17:03'),
(3345, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 18:17:58'),
(3346, 'MRP Scheduling Insert', '[ BOM failed to create ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-09 18:28:07'),
(3347, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-09 18:31:29'),
(3348, 'Estimate Insert', '[ Estimate successfully created ]', 'erp/sale/estimateview/58', '', 'Qbs ', '2024-12-09 18:35:59'),
(3349, 'MRP Scheduling Insert', '[ BOM failed to create ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-09 18:44:04'),
(3350, 'MRP Scheduling Insert', '[ BOM failed to create ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-09 18:45:49'),
(3351, 'MRP Scheduling Insert', '[ BOM failed to create ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-09 18:45:56'),
(3352, 'MRP Scheduling Insert', '[ BOM failed to create ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-09 18:46:06'),
(3353, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 18:47:31'),
(3354, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-09 19:29:20'),
(3355, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-10 10:10:17'),
(3356, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 12:03:25');
INSERT INTO `erp_log` (`log_id`, `title`, `log_text`, `ref_link`, `additional_info`, `done_by`, `created_at`) VALUES
(3357, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 12:04:25'),
(3358, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 12:04:44'),
(3359, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 12:51:46'),
(3360, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 14:24:23'),
(3361, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 14:25:50'),
(3362, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 14:27:38'),
(3363, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 14:31:22'),
(3364, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 14:32:28'),
(3365, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 14:33:18'),
(3366, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 14:34:08'),
(3367, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 15:47:02'),
(3368, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 15:49:35'),
(3369, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 15:56:14'),
(3370, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 16:08:14'),
(3371, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 16:32:23'),
(3372, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 16:38:36'),
(3373, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 16:50:59'),
(3380, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 17:45:21'),
(3382, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 18:01:35'),
(3384, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 18:04:57'),
(3385, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 18:05:22'),
(3389, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 18:30:47'),
(3390, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 18:31:52'),
(3391, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 19:00:18'),
(3392, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 19:11:33'),
(3393, 'Logout', '[ User successfully logout ]', '', '', 'Qbs ', '2024-12-10 19:18:27'),
(3394, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-10 19:18:30'),
(3395, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 19:20:09'),
(3396, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 19:20:54'),
(3397, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 19:23:13'),
(3398, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 19:25:39'),
(3399, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 19:26:38'),
(3400, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 19:27:42'),
(3401, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 19:28:39'),
(3402, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-10 19:30:42'),
(3403, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-11 10:19:31'),
(3404, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-11 10:20:36'),
(3405, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-11 10:27:28'),
(3406, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-11 10:46:47'),
(3407, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-11 10:50:46'),
(3408, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-11 10:51:26'),
(3409, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-11 11:22:11'),
(3410, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-11 14:15:36'),
(3411, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-12 10:25:12'),
(3412, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-12 10:27:26'),
(3413, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-12 11:27:13'),
(3414, 'Planning Insert', '[ Planning successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-12 14:17:52'),
(3415, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/13', '', 'Qbs ', '2024-12-12 14:26:00'),
(3416, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/13', '', 'Qbs ', '2024-12-12 14:27:54'),
(3417, 'Semi Finished Insert', '[ Semi Finished successfully created ]', '', '', 'Qbs ', '2024-12-12 14:40:26'),
(3418, 'Semi Finished Insert', '[ Semi Finished successfully created ]', '', '', 'Qbs ', '2024-12-12 14:42:25'),
(3419, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-12 14:47:02'),
(3420, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-12 14:57:41'),
(3421, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-12 19:19:18'),
(3422, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-13 10:20:44'),
(3423, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-13 11:02:42'),
(3424, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-13 11:08:47'),
(3425, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-13 11:31:51'),
(3426, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-13 11:32:12'),
(3427, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-13 11:42:54'),
(3428, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-13 11:43:04'),
(3429, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-13 14:50:40'),
(3430, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-13 15:16:16'),
(3431, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-13 15:16:16'),
(3432, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-13 15:35:49'),
(3433, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-13 16:34:54'),
(3434, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-13 17:53:33'),
(3435, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-13 17:58:06'),
(3436, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-13 18:23:24'),
(3437, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-13 18:24:07'),
(3438, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-13 18:26:10'),
(3439, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-13 18:26:44'),
(3440, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-13 18:34:58'),
(3441, 'Planning Insert', '[ Planning successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-13 19:10:53'),
(3442, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-14 10:12:29'),
(3443, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-14 10:13:59'),
(3444, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-14 10:29:05'),
(3445, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-14 10:40:06'),
(3446, 'Planning Update', '[ Planning successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-14 11:03:32'),
(3447, 'Planning Update', '[ Planning successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-14 11:05:20'),
(3448, 'Planning Update', '[ Planning successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-14 11:05:57'),
(3449, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/13', '', 'Qbs ', '2024-12-14 11:55:13'),
(3450, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-14 11:55:45'),
(3451, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/14', '', 'Qbs ', '2024-12-14 11:58:48'),
(3452, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/14', '', 'Qbs ', '2024-12-14 11:59:14'),
(3453, 'Planning Insert', '[ Planning successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-14 12:12:14'),
(3454, 'Planning Insert', '[ Planning successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-14 12:15:28'),
(3455, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-14 12:42:58'),
(3456, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/13', '', 'Qbs ', '2024-12-14 12:43:00'),
(3457, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-14 12:46:52'),
(3458, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/13', '', 'Qbs ', '2024-12-14 12:47:16'),
(3459, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/13', '', 'Qbs ', '2024-12-14 12:47:48'),
(3460, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/13', '', 'Qbs ', '2024-12-14 12:48:13'),
(3461, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-14 12:49:41'),
(3462, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-14 12:50:48'),
(3463, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-14 12:54:38'),
(3464, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/14', '', 'Qbs ', '2024-12-14 12:56:47'),
(3465, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/14', '', 'Qbs ', '2024-12-14 12:57:14'),
(3466, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/14', '', 'Qbs ', '2024-12-14 12:57:38'),
(3467, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-14 12:59:51'),
(3468, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-14 13:29:57'),
(3469, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-14 14:30:14'),
(3470, 'MRP Scheduling update', '[ BOM failed to updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-14 14:49:35'),
(3471, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/14', '', 'Qbs ', '2024-12-14 14:54:30'),
(3472, 'Planning Insert', '[ Planning successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-14 15:05:13'),
(3473, 'Planning Update', '[ Planning successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-schedule', '', 'Qbs ', '2024-12-14 15:05:58'),
(3474, 'Login', '[ User successfully logged in ]', '', '', 'Qbs ', '2024-12-14 17:23:35'),
(3475, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-14 18:18:26'),
(3476, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-14 18:20:57'),
(3477, 'MRP Scheduling Insert', '[BOM successfully created ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-14 18:28:58'),
(3478, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-14 18:38:54'),
(3479, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-14 18:41:41'),
(3480, 'MRP Scheduling update', '[BOM successfully updated ]', 'http://192.168.29.137/Erp/public/erp/mrp/planning-view/1', '', 'Qbs ', '2024-12-14 18:42:52');

-- --------------------------------------------------------

--
-- Table structure for table `erp_roles`
--

CREATE TABLE `erp_roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(60) NOT NULL,
  `role_desc` varchar(1000) NOT NULL,
  `permissions` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `referrer_role` int(11) NOT NULL,
  `can_be_purged` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `erp_roles`
--

INSERT INTO `erp_roles` (`role_id`, `role_name`, `role_desc`, `permissions`, `created_at`, `referrer_role`, `can_be_purged`) VALUES
(0, 'Employee', 'Employee ', '[]', '1718613327', 0, 0),
(11, 'Manager', 'Manages all work', '[\"crm_lead_view_global\",\"crm_lead_create\",\"crm_lead_update\",\"crm_lead_delete\",\"crm_customer_view_global\",\"crm_customer_create\",\"crm_customer_update\",\"crm_customer_delete\"]', '1643965958', 0, 1),
(15, 'Sales Rep', 'sales representative', '[\"crm_lead_view_own\",\"crm_lead_create\",\"crm_lead_update\",\"crm_lead_delete\",\"crm_customer_view_own\",\"crm_customer_create\",\"crm_customer_update\",\"crm_customer_delete\"]', '1643966205', 0, 1),
(16, 'test', 'sss', '[\"crm_lead_create\",\"crm_lead_update\",\"crm_customer_view_own\"]', '1643967631', 0, 1),
(17, 'test', 'test', '[\"do_reflect\",\"crm_lead_view_own\",\"crm_lead_create\",\"crm_lead_update\",\"crm_customer_view_own\",\"crm_customer_update\",\"crm_customer_delete\"]', '1643967665', 0, 1),
(18, 'test', 'sss', '[\"crm_lead_create\",\"crm_lead_update\",\"crm_customer_view_global\"]', '1643969374', 16, 1),
(19, 'Manager', 'Manages all work', '[]', '1703230264', 11, 0),
(20, 'Test Role', 'Test', '[\"crm_lead_view_global\",\"crm_lead_delete\",\"crm_customer_view_own\",\"crm_customer_update\",\"notify_create\"]', '1703230341', 0, 1),
(21, 'Sales Rep', 'sales representative', '[\"crm_lead_view_global\",\"crm_lead_create\",\"crm_lead_update\",\"crm_lead_delete\",\"crm_customer_view_own\",\"crm_customer_create\",\"crm_customer_update\",\"crm_customer_delete\"]', '1703834978', 15, 1),
(22, 'Sales Rep', 'sales representative', '[\"crm_lead_view_own\",\"crm_lead_create\",\"crm_lead_update\",\"crm_lead_delete\",\"crm_customer_view_own\",\"crm_customer_create\",\"crm_customer_update\",\"crm_customer_delete\"]', '1703835014', 21, 0),
(23, 'employee', 'tamil', '[\"crm_lead_view_global\",\"crm_customer_delete\"]', '1705754516', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `erp_settings`
--

CREATE TABLE `erp_settings` (
  `setting_id` int(11) NOT NULL,
  `s_name` varchar(255) NOT NULL,
  `s_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `erp_settings`
--

INSERT INTO `erp_settings` (`setting_id`, `s_name`, `s_value`) VALUES
(1, 'company_logo', 'logo.png'),
(2, 'favicon', 'favicon.png'),
(3, 'company_name', 'Q Brainstorm Software'),
(4, 'address', 'No.164, First Floor, Arcot Rd, Valasaravakkam'),
(5, 'city', 'Chennai'),
(6, 'state', 'Tamil Nadu'),
(7, 'country', 'India'),
(8, 'zip', '600087'),
(9, 'phone', '9080780700'),
(10, 'gst', ''),
(11, 'mail_engine', 'PHPMailer'),
(12, 'email_encryption', 'ssl'),
(13, 'smtp_host', 'smtp.gmail.com'),
(14, 'smtp_port', '465'),
(15, 'smtp_username', 'support@qbrainstorm.com'),
(16, 'smtp_password', 'support12345'),
(17, 'bcc_list', ''),
(18, 'cc_list', ''),
(19, 'track_quota', '0'),
(20, 'close_account_book', '2024-01-26'),
(21, 'finance_capital', '10000.00'),
(22, 'finance_capital_vary', '0.00'),
(23, 'system_type', 'manufacturing');

-- --------------------------------------------------------

--
-- Table structure for table `erp_users`
--

CREATE TABLE `erp_users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `last_name` varchar(120) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `position` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `expired` tinyint(4) NOT NULL DEFAULT 0,
  `is_admin` tinyint(4) NOT NULL DEFAULT 0,
  `last_login` varchar(20) NOT NULL,
  `role_id` int(11) NOT NULL,
  `remember` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `erp_users`
--

INSERT INTO `erp_users` (`user_id`, `name`, `staff_id`, `last_name`, `email`, `phone`, `position`, `password`, `active`, `expired`, `is_admin`, `last_login`, `role_id`, `remember`, `description`, `created_at`) VALUES
(1, 'Qbs ', 0, 'Support', 'support@qbrainstorm.com', '9080780700', 'admin1', '$2y$10$JcJTUdh3pp9H7h317ZzfpegXKpbQowDyHcWauUV7d0uQiLVZrnjWm', 1, 0, 1, '1657538814', 19, 'fcc923a6de504c37932e619e44128a4e3aedc51b', 'no descriptions', '1643791190'),
(2, 'John', 0, 'J', 'john@qbrainstorm.com', '1234567890', 'test', '$2y$10$rZ1Uu51LjTwSb1tIGJ4l7eWEMi83eAg6hQrJI2TS.bnNVqaWLAN.i', 1, 0, 0, '1646822983', 22, '', '0', '1643791190'),
(3, 'Jacob', 0, 'K', 'jacob@qbrainstorm.com', '9080780700', 'Purchase Manager', '$2y$10$Sv15lDTaSUefO7JPYo4VQehQB6pD7lXTwZH3Q8SGPLeQVWFG4WyjW', 1, 0, 0, '1648889292', 11, '92bdd0f09edabdde89c2a3e5a5bdfc3b5e580245', '0', '1643791243'),
(15, 'Udhaya', 14, 'Kumar', 'udhaya@qbrainstorm.com', '9080780700', 'Junior', '$2y$10$z/AEa4L6pFWKXyvUyW5me.JQtTL6T3D8aI8hOTMb.9PsFWnLsM4yO', 1, 0, 1, '', 19, '', 'des', '2024-06-17 15:41:21');

-- --------------------------------------------------------

--
-- Table structure for table `estimates`
--

CREATE TABLE `estimates` (
  `estimate_id` int(11) NOT NULL,
  `code` varchar(140) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `estimate_date` date NOT NULL,
  `terms_condition` text NOT NULL,
  `shippingaddr_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `updated_at` varchar(50) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estimates`
--

INSERT INTO `estimates` (`estimate_id`, `code`, `cust_id`, `estimate_date`, `terms_condition`, `shippingaddr_id`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 'es90', 37, '2024-02-05', 'fsd', 15, '1707138996', '1707138996', 1),
(41, 'e-12', 46, '2024-01-22', '<p>afdsf</p>', 0, '1698878592', '1733740282', 1),
(49, 'EST-0043', 37, '2024-02-05', '<p>fsdffsfsf</p>', 0, '1707140970', '1707993552', 1),
(50, 'EST-0050', 37, '2024-02-06', '<ol><li><h3>fdsfsdfds</h3></li><li><h2>fdsfas</h2></li><li><h2>fasdf</h2></li><li><h2>sdff</h2></li><li><h2>fdsf</h2></li><li><h2>fds</h2></li></ol>', 3, '1707197111', '1707197397', 1),
(52, 'EST-0051', 37, '2024-02-14', '<p>fsf</p>', 0, '1707915986', '1707917474', 1),
(53, 'EST-0053', 46, '2024-02-14', '<p>fsf</p>', 0, '1707917093', '1707917093', 1),
(54, 'EST-0054', 46, '2024-02-15', '<p>dsffsf test</p>', 0, '1707989671', '1707991768', 1),
(55, 'EST-0055', 37, '2024-04-27', '', 0, '1714200921', '1714200921', 1),
(56, 'EST-0056', 45, '2024-11-01', '<p>test</p>', 0, '1730469185', '1730469185', 1),
(57, 'EST-0057', 37, '2024-12-09', '<p>aaa</p>', 0, '1733739270', '1733739270', 1),
(58, 'EST-0058', 37, '2024-12-09', '', 0, '1733749559', '1733749559', 1);

-- --------------------------------------------------------

--
-- Table structure for table `estimate_items`
--

CREATE TABLE `estimate_items` (
  `est_item_id` int(11) NOT NULL,
  `estimate_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(14,2) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `tax1` tinyint(4) NOT NULL,
  `tax2` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estimate_items`
--

INSERT INTO `estimate_items` (`est_item_id`, `estimate_id`, `related_to`, `related_id`, `price_id`, `quantity`, `unit_price`, `amount`, `tax1`, `tax2`) VALUES
(1, 44, 'finished_good', 22, 2, 1, '3000.00', '3000.00', 18, 9),
(7, 32, 'finished_good', 22, 2, 1, '3000.00', '3000.00', 18, 9),
(8, 33, 'finished_good', 21, 1, 1, '2000.00', '2000.00', 9, 18),
(12, 36, 'finished_good', 25, 8, 1, '120.00', '120.00', 9, 18),
(14, 40, 'finished_good', 22, 2, 1, '3000.00', '3000.00', 18, 9),
(15, 41, 'finished_good', 25, 8, 1, '120.00', '120.00', 20, 18),
(17, 49, 'finished_good', 22, 2, 1, '3000.00', '3000.00', 18, 9),
(18, 50, 'finished_good', 22, 2, 1, '3000.00', '3000.00', 18, 9),
(19, 52, 'finished_good', 22, 2, 1, '3000.00', '3000.00', 18, 9),
(20, 53, 'finished_good', 22, 2, 1, '3000.00', '3000.00', 18, 9),
(21, 54, 'finished_good', 22, 2, 1, '3000.00', '3000.00', 18, 9),
(22, 54, 'finished_good', 25, 8, 1, '120.00', '120.00', 20, 18),
(23, 49, 'finished_good', 25, 8, 1, '120.00', '120.00', 20, 18),
(24, 55, 'finished_good', 25, 8, 2, '120.00', '240.00', 20, 18),
(25, 56, 'finished_good', 21, 1, 4, '2000.00', '8000.00', 9, 18),
(26, 57, 'finished_good', 25, 8, 2, '120.00', '240.00', 20, 18),
(27, 41, 'finished_good', 23, 1, 1, '2000.00', '2000.00', 9, 18),
(28, 58, 'finished_good', 21, 1, 4, '2000.00', '8000.00', 9, 18);

-- --------------------------------------------------------

--
-- Table structure for table `expense_items`
--

CREATE TABLE `expense_items` (
  `expense_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `quote_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(14,2) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `tax1` tinyint(4) NOT NULL,
  `tax2` tinyint(4) NOT NULL,
  `send_qty` int(11) NOT NULL,
  `return_qty` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expense_items`
--

INSERT INTO `expense_items` (`expense_id`, `related_to`, `related_id`, `price_id`, `quote_id`, `order_id`, `invoice_id`, `quantity`, `unit_price`, `amount`, `tax1`, `tax2`, `send_qty`, `return_qty`, `timestamp`) VALUES
(67, 'expense', 40, 0, 0, 0, 18, 1, '0.00', '50000.00', 9, 9, 0, 0, '2024-06-10 11:30:30'),
(68, 'expense', 40, 0, 0, 0, 19, 1, '0.00', '50000.00', 9, 9, 0, 0, '2024-06-10 11:56:38'),
(69, 'expense', 41, 0, 0, 0, 20, 1, '0.00', '5000.00', 9, 18, 0, 0, '2024-06-10 13:51:52'),
(70, 'expense', 42, 0, 0, 0, 21, 1, '0.00', '4000.00', 9, 9, 0, 0, '2024-06-11 07:02:07'),
(71, 'expense', 44, 0, 0, 0, 23, 1, '0.00', '69.00', 0, 0, 0, 0, '2024-11-18 07:59:25');

-- --------------------------------------------------------

--
-- Table structure for table `expense_task`
--

CREATE TABLE `expense_task` (
  `task_id` int(11) NOT NULL,
  `expense_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `related_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `assignees` int(11) NOT NULL,
  `followers` int(11) NOT NULL,
  `task_description` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expense_task`
--

INSERT INTO `expense_task` (`task_id`, `expense_id`, `name`, `status`, `start_date`, `due_date`, `related_id`, `priority`, `assignees`, `followers`, `task_description`, `created_by`, `created_at`) VALUES
(4, 40, 'Last Update', 3, '2024-06-06', '2024-06-23', 40, 1, 14, 14, 'aaaa', 1, '2024-06-07 12:06:52');

-- --------------------------------------------------------

--
-- Table structure for table `finished_goods`
--

CREATE TABLE `finished_goods` (
  `finished_good_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `short_desc` varchar(1000) NOT NULL,
  `long_desc` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `code` varchar(225) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `finished_goods`
--

INSERT INTO `finished_goods` (`finished_good_id`, `name`, `short_desc`, `long_desc`, `group_id`, `code`, `unit_id`, `brand_id`, `created_at`, `created_by`) VALUES
(21, 'test', 'rgawerh', 'wearghetdh', 13, 'dgedh465465', 2, 1, '1704177598', 1),
(22, 'a', 'adfgf', 'dfsgf', 13, 'SO128', 2, 1, '1704177621', 1),
(23, 'product1', 'fsda', 'agffsd', 14, 'dsfg4565', 2, 1, '1704863783', 1),
(24, 'oil', 'hyfty', 'ytf', 14, '23', 2, 2, '1704864633', 1),
(25, 'hairOil', 'test', 'test', 13, 'PD10', 2, 1, '1704865011', 1),
(26, 'QBrainstorm Software', 'aaaaaaaaa', 'aaaaaaaaa', 13, 'SKU0026', 1, 2, '1733566086', 1);

-- --------------------------------------------------------

--
-- Table structure for table `general_ledger`
--

CREATE TABLE `general_ledger` (
  `ledger_id` int(11) NOT NULL,
  `gl_acc_id` int(11) NOT NULL,
  `period` date NOT NULL,
  `actual_amt` decimal(16,2) NOT NULL,
  `balance_fwd` decimal(16,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `general_ledger`
--

INSERT INTO `general_ledger` (`ledger_id`, `gl_acc_id`, `period`, `actual_amt`, `balance_fwd`) VALUES
(1, 2, '2022-02-01', '0.00', '0.00'),
(2, 2, '2022-01-01', '0.00', '0.00'),
(9, 1, '2022-03-01', '4000.00', '4000.00'),
(10, 2, '2022-03-01', '-4000.00', '-4000.00'),
(17, 1, '2023-12-01', '-10000.00', '-10000.00'),
(18, 15, '2023-12-01', '0.00', '15.00'),
(21, 18, '2024-01-01', '0.00', '12254.00');

-- --------------------------------------------------------

--
-- Table structure for table `gl_accounts`
--

CREATE TABLE `gl_accounts` (
  `gl_acc_id` int(11) NOT NULL,
  `acc_group_id` int(11) NOT NULL,
  `account_code` smallint(6) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `cash_flow` tinyint(4) NOT NULL,
  `order_num` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gl_accounts`
--

INSERT INTO `gl_accounts` (`gl_acc_id`, `acc_group_id`, `account_code`, `account_name`, `cash_flow`, `order_num`, `created_at`, `created_by`) VALUES
(1, 3, 1010, 'petty cash', 4, 1, '1645248842', 1),
(2, 4, 1011, 'Advertise', 2, 1, '1645248923', 1),
(15, 8, 9999, 'test', 1, 3, '1703760391', 1),
(18, 4, 32767, '78767778', 2, 0, '1705750016', 1);

-- --------------------------------------------------------

--
-- Table structure for table `grn`
--

CREATE TABLE `grn` (
  `grn_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `remarks` text NOT NULL,
  `delivered_on` varchar(20) NOT NULL,
  `updated_on` varchar(20) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grn`
--

INSERT INTO `grn` (`grn_id`, `order_id`, `status`, `remarks`, `delivered_on`, `updated_on`, `updated_by`) VALUES
(1, 1, 1, 'damaged', '2024-12-03', '2024-12-03 14:53:13', 1),
(2, 2, 1, 'okay', '2023-12-15', '2023-12-27 10:33:28', 1),
(55846, 3, 1, 'rdyidrs', '2023-12-30', '2023-12-29 06:34:51', 1),
(55847, 9, 1, 'xdcfgxj', '2024-01-03', '2024-01-02 07:37:05', 1),
(55848, 1, 1, 'kkkk', '2024-12-20', '2024-12-03 14:55:42', 1),
(55849, 3, 1, 'test', '2024-01-14', '2024-01-12 10:47:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_requisition`
--

CREATE TABLE `inventory_requisition` (
  `invent_req_id` int(11) NOT NULL,
  `req_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_requisition`
--

INSERT INTO `inventory_requisition` (`invent_req_id`, `req_id`, `related_to`, `related_id`, `qty`) VALUES
(1, 1, 'raw_material', 43, 5),
(2, 1, 'raw_material', 53, 5),
(3, 2, 'raw_material', 53, 1),
(6, 5, 'raw_material', 47, 12),
(7, 6, 'semi_finished', 17, 12),
(8, 7, 'raw_material', 42, 4),
(9, 7, 'raw_material', 50, 4),
(10, 7, 'raw_material', 56, 4);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_services`
--

CREATE TABLE `inventory_services` (
  `invent_service_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `short_desc` varchar(1000) NOT NULL,
  `long_desc` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `code` varchar(140) NOT NULL,
  `price` decimal(14,2) NOT NULL,
  `tax1` int(11) NOT NULL,
  `tax2` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_services`
--

INSERT INTO `inventory_services` (`invent_service_id`, `name`, `short_desc`, `long_desc`, `group_id`, `code`, `price`, `tax1`, `tax2`, `created_at`, `created_by`) VALUES
(10, 'test', 'sdfharth', 'esrhryjhry', 19, 'SO128', '156532.00', 1, 1, '1704178086', 1),
(11, 'bottle', 'rexs', 'rse', 19, '12', '120.00', 1, 2, '1704864422', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_warehouse`
--

CREATE TABLE `inventory_warehouse` (
  `invent_house_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_warehouse`
--

INSERT INTO `inventory_warehouse` (`invent_house_id`, `warehouse_id`, `related_to`, `related_id`) VALUES
(41, 2, 'semi_finished', 42),
(42, 2, 'semi_finished', 7),
(43, 2, 'semi_finished', 8),
(46, 1, 'finished_good', 3),
(49, 2, 'finished_good', 4),
(50, 1, 'finished_good', 4),
(51, 2, 'finished_good', 5),
(52, 1, 'finished_good', 5),
(55, 0, 'raw_material', 18),
(56, 0, 'raw_material', 16),
(57, 0, 'raw_material', 25),
(58, 9, 'raw_material', 26),
(59, 8, 'raw_material', 26),
(60, 6, 'raw_material', 26),
(61, 9, 'raw_material', 27),
(62, 8, 'raw_material', 27),
(63, 6, 'raw_material', 27),
(64, 9, 'raw_material', 28),
(65, 8, 'raw_material', 28),
(66, 6, 'raw_material', 28),
(67, 9, 'raw_material', 29),
(68, 8, 'raw_material', 29),
(69, 6, 'raw_material', 29),
(70, 9, 'raw_material', 30),
(71, 8, 'raw_material', 30),
(72, 6, 'raw_material', 30),
(73, 9, 'raw_material', 31),
(74, 8, 'raw_material', 31),
(75, 6, 'raw_material', 31),
(76, 9, 'raw_material', 32),
(77, 8, 'raw_material', 32),
(78, 6, 'raw_material', 32),
(79, 9, 'raw_material', 33),
(80, 8, 'raw_material', 33),
(81, 6, 'raw_material', 33),
(82, 9, 'raw_material', 34),
(83, 8, 'raw_material', 34),
(84, 6, 'raw_material', 34),
(85, 9, 'raw_material', 35),
(86, 8, 'raw_material', 35),
(87, 6, 'raw_material', 35),
(88, 9, 'raw_material', 36),
(89, 8, 'raw_material', 36),
(90, 6, 'raw_material', 36),
(91, 9, 'raw_material', 37),
(92, 8, 'raw_material', 37),
(93, 6, 'raw_material', 37),
(94, 9, 'raw_material', 38),
(95, 8, 'raw_material', 38),
(96, 6, 'raw_material', 38),
(97, 9, 'raw_material', 39),
(98, 8, 'raw_material', 39),
(99, 6, 'raw_material', 39),
(100, 9, 'raw_material', 40),
(101, 8, 'raw_material', 40),
(102, 6, 'raw_material', 40),
(103, 9, 'raw_material', 41),
(104, 8, 'raw_material', 41),
(105, 6, 'raw_material', 41),
(112, 11, 'semi_finished', 9),
(118, 11, 'semi_finished', 10),
(119, 11, 'semi_finished', 11),
(120, 9, 'semi_finished', 11),
(121, 8, 'semi_finished', 11),
(122, 7, 'semi_finished', 11),
(123, 6, 'semi_finished', 11),
(124, 4, 'semi_finished', 11),
(125, 3, 'semi_finished', 11),
(126, 11, 'finished_good', 6),
(128, 11, 'finished_good', 7),
(135, 11, 'finished_good', 15),
(136, 9, 'finished_good', 15),
(137, 8, 'finished_good', 15),
(138, 6, 'finished_good', 15),
(139, 4, 'finished_good', 15),
(140, 0, 'raw_material', 2),
(141, 0, 'raw_material', 3),
(142, 0, 'raw_material', 17),
(143, 0, 'raw_material', 20),
(144, 0, 'raw_material', 21),
(145, 0, 'raw_material', 24),
(146, 0, 'raw_material', 42),
(147, 0, 'raw_material', 43),
(148, 9, 'raw_material', 44),
(149, 8, 'raw_material', 44),
(152, 0, 'raw_material', 45),
(154, 0, 'raw_material', 47),
(155, 0, 'raw_material', 46),
(157, 11, 'raw_material', 49),
(160, 0, 'raw_material', 48),
(163, 2, 'finished_good', 2),
(164, 13, 'finished_good', 16),
(165, 13, 'finished_good', 17),
(166, 13, 'finished_good', 18),
(167, 0, 'raw_material', 50),
(168, 13, 'semi_finished', 12),
(170, 6, 'semi_finished', 13),
(171, 1, 'semi_finished', 14),
(172, 2, 'semi_finished', 14),
(173, 8, 'semi_finished', 14),
(174, 0, 'raw_material', 51),
(175, 0, 'raw_material', 52),
(177, 0, 'raw_material', 53),
(178, 8, 'semi_finished', 15),
(179, 4, 'semi_finished', 16),
(180, 6, 'semi_finished', 17),
(181, 11, 'finished_good', 19),
(182, 8, 'finished_good', 20),
(183, 9, 'finished_good', 21),
(184, 2, 'finished_good', 22),
(185, 9, 'raw_material', 54),
(186, 9, 'raw_material', 55),
(188, 0, 'raw_material', 56),
(189, 9, 'raw_material', 57),
(191, 0, 'raw_material', 58),
(193, 0, 'raw_material', 59),
(197, 0, 'raw_material', 61),
(199, 0, 'raw_material', 62),
(202, 0, 'raw_material', 63),
(203, 13, 'raw_material', 64),
(204, 11, 'raw_material', 64),
(205, 9, 'raw_material', 64),
(206, 8, 'raw_material', 64),
(207, 6, 'raw_material', 64),
(208, 4, 'raw_material', 64),
(209, 2, 'raw_material', 64),
(210, 1, 'raw_material', 64),
(211, 4, 'raw_material', 65),
(212, 0, 'raw_material', 60),
(214, 0, 'raw_material', 66),
(215, 2, 'raw_material', 67),
(216, 4, 'semi_finished', 18),
(217, 11, 'finished_good', 23),
(218, 9, 'finished_good', 24),
(219, 11, 'finished_good', 25),
(221, 11, 'raw_material', 69),
(223, 0, 'raw_material', 70),
(225, 0, 'raw_material', 68),
(226, 4, 'finished_good', 26),
(227, 9, 'semi_finished', 1),
(228, 9, 'semi_finished', 2);

-- --------------------------------------------------------

--
-- Table structure for table `journal_entry`
--

CREATE TABLE `journal_entry` (
  `journal_id` int(11) NOT NULL,
  `gl_acc_id` int(11) NOT NULL,
  `credit` tinyint(1) NOT NULL,
  `debit` tinyint(1) NOT NULL,
  `narration` text NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `posted` tinyint(1) NOT NULL DEFAULT 0,
  `posted_date` varchar(20) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `prev_amount` decimal(14,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `journal_entry`
--

INSERT INTO `journal_entry` (`journal_id`, `gl_acc_id`, `credit`, `debit`, `narration`, `amount`, `transaction_date`, `type`, `posted`, `posted_date`, `created_at`, `created_by`, `related_to`, `related_id`, `prev_amount`) VALUES
(21, 1, 0, 1, '[ Finance Automation added Marketing Entry ]', '2000.00', '2022-03-02', 0, 1, '2022-03-02 18:05:11', '1646224511', 1, 'marketing', 1, '2000.00'),
(22, 2, 1, 0, '[ Finance Automation added Marketing Entry ]', '2000.00', '2022-03-02', 0, 1, '2022-03-02 18:05:11', '1646224511', 1, 'marketing', 1, '2000.00'),
(23, 1, 0, 1, '[ Finance Automation added Marketing Entry ]', '2000.00', '2022-03-02', 0, 1, '2022-03-02 18:08:51', '1646224731', 1, 'marketing', 1, '2000.00'),
(24, 2, 1, 0, '[ Finance Automation added Marketing Entry ]', '2000.00', '2022-03-02', 0, 1, '2022-03-02 18:08:51', '1646224731', 1, 'marketing', 1, '2000.00'),
(25, 1, 1, 0, '', '5000.00', '2023-12-24', 0, 1, '2023-12-23', '1703311285', 1, '', 0, '0.00'),
(26, 1, 0, 1, '', '5000.00', '2023-12-24', 0, 1, '2023-12-23', '1703311285', 1, '', 0, '0.00'),
(27, 1, 1, 0, '', '5000.00', '2023-12-24', 0, 1, '2024-01-09', '1703311401', 1, '', 0, '0.00'),
(28, 1, 0, 1, '', '5000.00', '2023-12-24', 0, 0, '', '1703311401', 1, '', 0, '0.00'),
(29, 1, 1, 0, '', '5000.00', '2023-12-24', 0, 0, '', '1703311809', 1, '', 0, '0.00'),
(30, 1, 0, 1, '', '5000.00', '2023-12-24', 0, 0, '', '1703311809', 1, '', 0, '0.00'),
(31, 1, 1, 0, '', '5000.00', '2023-12-24', 0, 1, '2023-12-26', '1703311839', 1, '', 0, '0.00'),
(33, 1, 1, 0, 'edsgatz', '1000.00', '2023-12-28', 1, 0, '', '1703843930', 1, '', 0, '0.00'),
(34, 2, 0, 1, 'sdzfvwgatf', '1000.00', '2023-12-28', 1, 0, '', '1703843930', 1, '', 0, '0.00'),
(35, 2, 0, 1, '', '500.00', '2024-01-09', 0, 0, '', '1704802663', 1, '', 0, '0.00'),
(36, 1, 0, 1, '', '500.00', '2024-01-09', 0, 0, '', '1704802663', 1, '', 0, '0.00'),
(37, 1, 0, 1, '', '100.00', '2024-01-09', 0, 0, '', '1704802663', 1, '', 0, '0.00'),
(38, 1, 1, 0, '', '1100.00', '2024-01-09', 0, 0, '', '1704802663', 1, '', 0, '0.00'),
(39, 1, 1, 0, 'fhsfh', '100.00', '2024-01-19', 0, 0, '', '1705750247', 1, '', 0, '0.00'),
(40, 2, 0, 1, 'vdhsdf', '100.00', '2024-01-19', 0, 0, '', '1705750247', 1, '', 0, '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `knowledgebase_groups`
--

CREATE TABLE `knowledgebase_groups` (
  `group_id` int(100) NOT NULL,
  `group_name` varchar(100) NOT NULL,
  `short_description` varchar(100) NOT NULL,
  `group_order` int(100) NOT NULL,
  `disabled` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `knowledgebase_groups`
--

INSERT INTO `knowledgebase_groups` (`group_id`, `group_name`, `short_description`, `group_order`, `disabled`) VALUES
(96, 'group', 'hello', 6, 0),
(97, 'group_2', 'none', 2, 0),
(98, 'ssaaas', 'aasasasas', 0, 0),
(99, 'cvbvcb', 'vcvbvcb', 0, 0),
(100, 'ddsdfsdfsdfsdfdf', 'dfdfdfdf', 111, 0),
(101, 'fgfgf', 'gfgfgfg', 111, 0),
(102, 'sdfdfdf', 'dffdffdfdf', 0, 0),
(103, 'sdfdfdfdf111', 'dfdfdfdfdfds', 0, 0),
(104, 'gfhghg', 'ghghgh', 0, 0),
(105, 'yuiyh', 'hjjhjhj', 0, 0),
(106, 'QBrainstorm Software', 'aaaaa', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `knowledge_base`
--

CREATE TABLE `knowledge_base` (
  `article_id` int(100) NOT NULL,
  `article_subject` varchar(100) NOT NULL,
  `article_group_id` int(100) NOT NULL,
  `Internal_article` int(100) NOT NULL,
  `vote` int(255) NOT NULL DEFAULT 0,
  `disabled` int(100) NOT NULL,
  `article_description` longtext NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `knowledge_base`
--

INSERT INTO `knowledge_base` (`article_id`, `article_subject`, `article_group_id`, `Internal_article`, `vote`, `disabled`, `article_description`, `date_added`) VALUES
(13, 'About Quotation ', 96, 0, 0, 0, '<p>Quotation is provided on the basis of information on hand. All additional tests, repairs, spare parts etc. are not included in this quotation. 3. Freight costs for overseas delivery, local harbour taxi / barge service and crane usage costs etc. are not included in this quotation. 4. Technicians and Servicing Equipment is subject to availability upon receipt of Purchase Order confirmation.</p>', '2024-11-22 06:55:28'),
(14, 'About Ktm-390', 97, 0, 0, 0, '<p>A_2</p>', '2024-12-03 07:00:35'),
(15, 'Testing purpose', 96, 0, 0, 0, '<p>The value of testing purpose</p>', '2024-12-03 05:44:43'),
(16, 'test article', 98, 0, 0, 0, '<p>nothing is important than life</p>', '2024-12-03 07:00:54'),
(17, 'Testing case', 99, 0, 0, 0, '<p>ddemo case the byagsd</p>', '2024-12-03 07:01:03');

-- --------------------------------------------------------

--
-- Table structure for table `knowledge_base_feedback`
--

CREATE TABLE `knowledge_base_feedback` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `user_type` varchar(225) NOT NULL,
  `article_id` int(11) NOT NULL,
  `positive` int(255) NOT NULL,
  `negative` int(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `knowledge_base_feedback`
--

INSERT INTO `knowledge_base_feedback` (`id`, `customer_id`, `user_type`, `article_id`, `positive`, `negative`, `created_at`) VALUES
(20, 21, 'client', 15, 1, 0, '2024-12-03 12:57:12'),
(21, 21, 'client', 16, 1, 0, '2024-12-03 12:58:21'),
(22, 21, 'client', 13, 1, 0, '2024-12-03 14:20:33');

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `lead_id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `assigned_to` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `position` varchar(140) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `company` varchar(140) NOT NULL,
  `description` text NOT NULL,
  `remarks` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`lead_id`, `source_id`, `status`, `assigned_to`, `name`, `position`, `address`, `city`, `state`, `country`, `zip`, `phone`, `email`, `website`, `company`, `description`, `remarks`, `created_at`, `created_by`, `updated_at`) VALUES
(1, 2, 4, 1, 'TDWWS', 'test', 'Alvarpettai ', 'Chennai', 'Tamil Nadu', 'India', '600087', '9564784521', 'Demo@test.com', 'www.TDWWS.com', 'GB BABA ', 'demo', '', '1714196976', 1, NULL),
(5, 1, 4, 2, 'john', 'Purchase Manager', 'No.164, First Floor, Arcot Rd, Valasaravakkam', 'Chennai', 'Tamil Nadu', 'India', '600087', '09080780700', 'support@example.com', 'www.qbrainstorm.com', 'john enterprise', 'gh', '', '', 1, NULL),
(8, 3, 0, 3, 'admin', 'test', 'test', 'test', 'test', 'test', 'test', 'test', '9876543210', 'support@gmail.com', 'test', 'test', '', '', 0, NULL),
(9, 2, 4, 2, 'shankar', 'manager', 'test', 'chennai', 'tamilnadu', 'india', '6000125', '9876543210', 'shankar@qqq.com', '', 'Shankar Company', '\r', '', '', 0, NULL),
(10, 1, 3, 3, 'Saran', 'Sales', 'test', 'chennai', 'tamilnadu', 'india', '6000125', '9876543210', 'saran@qqq.com', '', 'Shankar Company', 'test', '', '', 0, NULL),
(11, 1, 0, 3, 'Raj', 'Driver', 'test', 'chennai', 'tamilnadu', 'india', '6000125', '9876543210', 'raj@qqq.com', '', 'Shankar Company', 'demo', '', '', 0, NULL),
(15, 1, 1, 3, 'kumar', '63680954', 'No Position', '-', 'Dhrmapuri', 'TN', 'INDIA', '636809', '9874561230', '57665', '78', 'kumar@gmail.com', '', '1703061296', 1, NULL),
(19, 3, 1, 2, 'Thamizharasi', 'web developer', 'chennai', 'Thiruvallur', 'tamilnadu', 'India', '602024', '01234567890', 'test@gmail.com', '', 'qbs', 'sdgagdsfgs', 'gfdsheshsdh', '1705746799', 1, NULL),
(20, 4, 0, 3, 'sgh', 'gfsgff', '35', 'chennai', 'tamilnadu', 'india', '600087', '8556654', 'tamil@gmail.com', '', 'qbs', 'cdbsdhsdh\r', '', '1705747957', 1, NULL),
(21, 2, 1, 2, 'name', 'devl', 'test', 'chennai', 'TN', 'india', '636809', '1234567890', 'pasupthi@gamil.com', 'www.test.com', 'test', 'tesst\r', '', '1705748139', 1, NULL),
(22, 1, 0, 1, 'Q Brainstorm', 'test', 'test 1', 'Chennai', 'Tamil Nadu', 'India', '600087', '09080780700', 'support@qbrainstorm.com', 'test', 'ecommerce website development company in chennai', 'test', 'test', '1714368375', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lead_source`
--

CREATE TABLE `lead_source` (
  `source_id` int(11) NOT NULL,
  `source_name` varchar(40) NOT NULL,
  `marketing_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lead_source`
--

INSERT INTO `lead_source` (`source_id`, `source_name`, `marketing_id`) VALUES
(1, 'Ads', 0),
(2, 'Google', 0),
(3, 'Facebook', 0),
(4, 'Email Marketing', 0),
(5, 'Tellecaller', 1),
(6, 'twitter', 2),
(7, 'instagram ads', 3),
(32, 'offline', 28),
(33, 'affiliate', 29);

-- --------------------------------------------------------

--
-- Table structure for table `marketing`
--

CREATE TABLE `marketing` (
  `marketing_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `done_by` varchar(140) NOT NULL,
  `company` varchar(140) NOT NULL,
  `address` varchar(1000) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `email` varchar(255) NOT NULL,
  `phone1` varchar(13) NOT NULL,
  `phone2` varchar(13) NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marketing`
--

INSERT INTO `marketing` (`marketing_id`, `name`, `related_to`, `related_id`, `amount`, `done_by`, `company`, `address`, `active`, `email`, `phone1`, `phone2`, `description`, `created_at`, `created_by`) VALUES
(2, 'Kemar', 'finished_good', 21, '3000.00', 'kevin', 'test company', '', 1, 'kumar@gmail.com', '09845612304', '8428054262', 'test', '1645790766', 1),
(3, 'gear ad', 'semi_finished', 1, '2000.00', 'joe', '', 'test', 1, '', '+919876543210', '', 'test', '1645792193', 1),
(29, 'Kumar', 'semi_finished', 1, '34.35', 'ghhth', 'qbrainstorm', 'Test Address\r\ntest address', 1, 'support@qbrainstorm.com', '09080780700', '09080780708', '4343434343', '1703078657', 1);

-- --------------------------------------------------------

--
-- Table structure for table `master`
--

CREATE TABLE `master` (
  `master_id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master`
--

INSERT INTO `master` (`master_id`, `name`, `email`, `password`) VALUES
(1, 'Master', 'support@qbrainstorm.com', '$2y$10$FCznQZaoK.VRnpkl3YmbYu8gRm.z5fsRRPHZq.r3.0vnZAl6RVgt6');

-- --------------------------------------------------------

--
-- Table structure for table `mrp_bom`
--

CREATE TABLE `mrp_bom` (
  `bom_id` int(200) NOT NULL,
  `product_id` int(200) NOT NULL,
  `related_to` varchar(200) NOT NULL,
  `mrp_scheduling_id` int(255) NOT NULL,
  `warhouse_id` int(11) NOT NULL,
  `quantity` int(200) NOT NULL,
  `material_id` int(200) NOT NULL,
  `planning_id` int(100) NOT NULL,
  `material_related_to` varchar(200) NOT NULL,
  `material_consumption` int(200) NOT NULL,
  `created_by` varchar(200) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mrp_dataset_forecast`
--

CREATE TABLE `mrp_dataset_forecast` (
  `id` int(200) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `related_to` varchar(200) NOT NULL,
  `related_id` int(200) NOT NULL,
  `quantity` int(200) NOT NULL,
  `current_stocks_on_inventory` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mrp_dataset_forecast`
--

INSERT INTO `mrp_dataset_forecast` (`id`, `timestamp`, `related_to`, `related_id`, `quantity`, `current_stocks_on_inventory`) VALUES
(1, '2023-01-18 11:31:37', 'finished_good', 22, 1, 1),
(2, '2023-11-20 04:15:30', 'finished_good', 21, 114, 88),
(3, '2023-06-21 14:56:41', 'finished_good', 21, 94, 97),
(4, '2023-08-30 00:23:36', 'finished_good', 22, 112, 110),
(5, '2023-12-23 23:14:30', 'finished_good', 22, 108, 109),
(6, '2023-06-28 08:07:40', 'finished_good', 23, 81, 84),
(7, '2023-12-17 19:51:53', 'finished_good', 23, 85, 97),
(8, '2023-01-16 22:11:53', 'finished_good', 24, 107, 112),
(9, '2023-02-20 07:35:36', 'finished_good', 24, 91, 72),
(10, '2023-01-10 12:22:04', 'finished_good', 25, 85, 79),
(11, '2023-04-04 08:42:28', 'finished_good', 25, 98, 85),
(12, '2023-04-09 18:05:19', 'finished_good', 21, 90, 85),
(13, '2023-11-18 07:27:45', 'finished_good', 21, 89, 100),
(14, '2023-12-26 02:14:30', 'finished_good', 22, 111, 111),
(15, '2023-07-28 01:27:45', 'finished_good', 22, 98, 82),
(16, '2023-11-05 21:42:05', 'finished_good', 23, 104, 96),
(17, '2023-11-10 05:46:29', 'finished_good', 23, 113, 81),
(18, '2023-07-06 10:51:12', 'finished_good', 24, 108, 81),
(19, '2023-10-26 15:37:19', 'finished_good', 24, 84, 108),
(20, '2023-12-24 07:41:45', 'finished_good', 25, 87, 99),
(21, '2023-08-31 01:02:31', 'finished_good', 25, 109, 93),
(22, '2023-03-03 17:33:17', 'finished_good', 21, 120, 88),
(23, '2023-07-06 19:58:05', 'finished_good', 21, 96, 82),
(24, '2023-11-02 03:48:48', 'finished_good', 22, 80, 108),
(25, '2023-05-18 13:49:24', 'finished_good', 22, 101, 120),
(26, '2023-05-04 07:20:35', 'finished_good', 23, 94, 78),
(27, '2023-05-16 16:29:05', 'finished_good', 23, 105, 96),
(28, '2023-04-10 04:39:32', 'finished_good', 24, 83, 98),
(29, '2023-08-01 17:41:37', 'finished_good', 24, 112, 104),
(30, '2023-01-12 07:06:14', 'finished_good', 25, 120, 116),
(31, '2023-09-12 05:48:40', 'finished_good', 25, 99, 118),
(32, '2023-05-07 17:00:34', 'finished_good', 21, 117, 103),
(33, '2023-07-21 22:00:38', 'finished_good', 21, 83, 99),
(34, '2023-08-06 05:18:27', 'finished_good', 22, 118, 106),
(35, '2023-01-19 16:44:14', 'finished_good', 22, 98, 83),
(36, '2023-08-12 04:29:52', 'finished_good', 23, 117, 106),
(37, '2023-06-26 09:55:19', 'finished_good', 23, 97, 91),
(38, '2023-02-25 21:33:51', 'finished_good', 24, 80, 99),
(39, '2023-11-27 04:35:23', 'finished_good', 24, 116, 118),
(40, '2023-01-09 03:47:44', 'finished_good', 25, 82, 87),
(41, '2023-07-04 15:52:53', 'finished_good', 25, 118, 110),
(42, '2023-08-23 00:54:01', 'finished_good', 21, 89, 98),
(43, '2023-11-01 05:31:20', 'finished_good', 21, 86, 101),
(44, '2023-09-23 19:30:50', 'finished_good', 22, 106, 101),
(45, '2023-04-21 02:27:00', 'finished_good', 22, 119, 93),
(46, '2023-02-11 15:26:15', 'finished_good', 23, 116, 79),
(47, '2023-09-21 11:59:11', 'finished_good', 23, 97, 89),
(48, '2023-06-17 02:21:57', 'finished_good', 24, 118, 78),
(49, '2023-02-21 13:11:00', 'finished_good', 24, 86, 114),
(50, '2023-07-06 10:05:14', 'finished_good', 25, 88, 117),
(51, '2023-03-26 10:02:29', 'finished_good', 25, 86, 103),
(52, '2024-01-23 07:57:44', 'finished_good', 21, 1, 5),
(53, '2024-01-27 10:29:40', 'finished_good', 21, 1, 5),
(54, '2024-02-06 09:39:53', 'finished_good', 25, 1, 2),
(55, '2024-02-06 09:42:59', 'finished_good', 25, 1, 2),
(56, '2024-02-06 09:43:31', 'finished_good', 25, 1, 2),
(57, '2024-02-06 09:46:41', 'finished_good', 25, 1, 2),
(58, '2024-02-08 04:43:44', 'finished_good', 22, 1, 1),
(59, '2024-02-08 04:44:58', 'finished_good', 23, 1, 1),
(60, '2024-02-08 05:47:10', 'finished_good', 21, 1, 5),
(61, '2024-02-08 05:47:51', 'finished_good', 22, 1, 1),
(62, '2024-02-08 05:48:26', 'finished_good', 25, 2, 2),
(63, '2024-02-14 13:41:26', 'finished_good', 23, 1, 1),
(64, '2024-02-14 13:41:26', 'finished_good', 21, 2, 5),
(65, '2024-02-14 13:41:47', 'finished_good', 23, 1, 1),
(66, '2024-02-14 13:41:47', 'finished_good', 21, 2, 5),
(67, '2024-02-14 13:42:11', 'finished_good', 25, 2, 2),
(68, '2024-02-14 13:42:44', 'finished_good', 22, 1, 1),
(69, '2024-02-14 13:42:44', 'finished_good', 23, 1, 1),
(70, '2024-02-15 12:16:17', 'finished_good', 25, 1, 2),
(71, '2024-02-15 12:16:17', 'finished_good', 22, 1, 1),
(72, '2024-02-15 12:23:30', 'finished_good', 25, 1, 2),
(73, '2024-02-15 12:23:30', 'finished_good', 22, 1, 1),
(74, '2024-02-15 12:24:32', 'finished_good', 25, 1, 2),
(75, '2024-02-15 12:24:32', 'finished_good', 22, 1, 1),
(76, '2024-02-15 12:33:48', 'finished_good', 22, 1, 1),
(77, '2024-02-15 12:33:48', 'finished_good', 23, 1, 1),
(78, '2024-02-15 12:33:59', 'finished_good', 22, 1, 1),
(79, '2024-02-15 12:33:59', 'finished_good', 23, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mrp_scheduling`
--

CREATE TABLE `mrp_scheduling` (
  `mrp_scheduling_id` int(11) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `bin_name` varchar(140) NOT NULL,
  `mfg_date` date NOT NULL,
  `batch_no` varchar(140) NOT NULL,
  `lot_no` varchar(140) NOT NULL,
  `stock` int(11) NOT NULL,
  `planning_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mrp_scheduling`
--

INSERT INTO `mrp_scheduling` (`mrp_scheduling_id`, `sku`, `related_to`, `related_id`, `warehouse_id`, `price_id`, `bin_name`, `mfg_date`, `batch_no`, `lot_no`, `stock`, `planning_id`) VALUES
(1, 'PD10', 'finished_good', 25, 1, 1, 'b-name', '2024-12-09', '100', '285', 14, 1),
(2, 'dsfg4565', 'finished_good', 23, 2, 2, 'test', '2024-12-06', '121', '2123312', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notify_id` int(11) NOT NULL,
  `title` varchar(140) NOT NULL,
  `notify_text` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `notify_email` tinyint(1) NOT NULL DEFAULT 0,
  `notify_at` datetime NOT NULL,
  `is_notified` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` int(11) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `related_to` varchar(100) NOT NULL,
  `related_id` int(11) NOT NULL,
  `related_base_url` varchar(100) NOT NULL,
  `job_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notify_id`, `title`, `notify_text`, `status`, `user_id`, `notify_email`, `notify_at`, `is_notified`, `created_at`, `updated_at`, `created_by`, `related_to`, `related_id`, `related_base_url`, `job_id`) VALUES
(5, 'Product Inspection', 'Kindly Inspect the Product Before sending it.', 0, 1, 0, '2024-06-14 14:10:00', 0, '2024-06-08', 0, 1, 'quotation', 2, 'erp.sale.quotations.view', 0),
(6, 'For Ashoke', 'Kindly see if Products are in good shape', 0, 1, 0, '2024-06-14 14:10:00', 0, '2024-06-08', 0, 1, 'quotation', 2, 'erp.sale.quotations.view', 0),
(14, 'Expense Reminder', 'save this', 0, 16, 1, '2024-06-14 14:10:00', 0, '2024-06-08', 0, 1, 'expense', 40, 'erp.expenses.view.page', 0),
(15, 'helloo', 'ghjgfgh', 0, 3, 1, '2024-06-14 14:10:00', 0, '2024-06-14', 0, 1, 'sale_invoice', 19, '', 146),
(16, 'helloo', 'wqwqwqw', 0, 1, 0, '0000-00-00 00:00:00', 0, '0000-00-00', 0, 1, 'sale_invoice', 19, '', 147),
(17, 'Expense Reminder', 'a', 0, 14, 0, '2024-06-12 19:22:00', 0, '2024-06-10', 0, 1, 'expense', 41, 'erp.expenses.view.page', 0),
(28, 'Goals Notification', 'not_goal_message_success', 0, 16, 0, '2024-06-14 12:04:55', 1, '2024-06-14', 0, 1, 'Goal', 6, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `packs`
--

CREATE TABLE `packs` (
  `pack_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `capacity` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packs`
--

INSERT INTO `packs` (`pack_id`, `name`, `capacity`, `related_to`, `related_id`, `height`, `width`, `description`, `created_at`, `created_by`) VALUES
(1, 'test', 25, 'finished_good', 4, 0, 0, 'good', '1647931364', 1),
(2, 'Pack1', 25, 'raw_material', 2, 0, 0, '', '1647931411', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pack_records`
--

CREATE TABLE `pack_records` (
  `pack_rec_id` int(11) NOT NULL,
  `pack_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `grn_id` int(11) NOT NULL,
  `mfg_date` varchar(20) NOT NULL,
  `batch_no` varchar(140) NOT NULL,
  `lot_no` varchar(140) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pack_unit`
--

CREATE TABLE `pack_unit` (
  `pack_unit_id` int(11) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `bin_name` varchar(140) NOT NULL,
  `mfg_date` date NOT NULL,
  `batch_no` varchar(140) NOT NULL,
  `lot_no` varchar(140) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pack_unit`
--

INSERT INTO `pack_unit` (`pack_unit_id`, `sku`, `related_to`, `related_id`, `bin_name`, `mfg_date`, `batch_no`, `lot_no`) VALUES
(2, '54', 'raw_material', 2, '', '2022-02-04', '', ''),
(3, '4', 'raw_material', 2, '', '2022-02-01', '', ''),
(4, '4', 'raw_material', 2, '', '2022-02-06', '', '\r\n'),
(5, '5', 'raw_material', 2, '', '2022-02-07', '', '\r\n'),
(6, '6', 'raw_material', 2, '', '2022-02-08', '', '\r\n'),
(7, '7', 'raw_material', 2, '', '2022-02-09', '', '\r\n'),
(8, '8', 'raw_material', 2, '', '2022-02-10', '', '\r\n'),
(10, '13', 'raw_material', 16, '', '2022-02-15', '', '\r\n'),
(11, '14', 'raw_material', 16, '', '2022-02-16', '', '\r\n'),
(12, '15', 'raw_material', 16, '', '2022-02-17', '', '\r\n'),
(13, '16', 'raw_material', 16, '', '2022-02-18', '', '\r\n'),
(14, '17', 'raw_material', 16, '', '2022-02-19', '', '\r\n'),
(15, '18', 'raw_material', 16, '', '2022-02-20', '', '\r\n'),
(16, 'ABC12', 'finished_good', 4, '123', '2022-04-04', '', ''),
(17, 'ABC13', 'finished_good', 4, '', '2022-04-04', '', ''),
(18, 'ABC135', 'finished_good', 4, '1235', '2022-04-29', '', ''),
(21, '85', 'finished_good', 21, 'tamil', '2024-01-03', '10', '55'),
(22, '12', 'semi_finished', 17, '3', '2024-01-04', '2', '3'),
(23, '26', 'semi_finished', 17, '8', '2024-01-03', '2', '7'),
(24, '123', 'finished_good', 22, 'tt', '2024-01-19', 'tts', 'sf'),
(25, 'dsh868', 'finished_good', 25, '9', '2024-01-10', '2', '3'),
(26, 'TEST1234', 'finished_good', 21, 'B44', '2024-01-10', '5', '801'),
(27, '32', 'semi_finished', 18, 'e', '2024-01-13', 'fd', 'fsdf'),
(28, 'sk1', 'finished_good', 25, '23', '2024-01-26', '432', 'dsf'),
(29, '#12345', 'finished_good', 21, 'A5', '2024-01-28', '10', '12'),
(31, 'SKU56', 'raw_material', 50, 'BIN34', '2024-01-27', '45', '16'),
(32, 'SKU57', 'finished_good', 21, 'BIN34', '2024-01-20', '45', '16'),
(33, 'SKU58', 'semi_finished', 17, 'BIN34', '2024-01-20', '45', '16'),
(34, 'SKU59', 'raw_material', 50, 'BIN34', '2024-01-23', '45', '16'),
(35, 'SKU60', 'finished_good', 23, 'BIN34', '2024-01-22', '45', '16'),
(36, 'SKU61', 'raw_material', 47, 'BIN34', '2024-01-24', '45', '16');

-- --------------------------------------------------------

--
-- Table structure for table `payment_modes`
--

CREATE TABLE `payment_modes` (
  `payment_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `description` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_modes`
--

INSERT INTO `payment_modes` (`payment_id`, `name`, `description`, `active`, `created_at`, `created_by`) VALUES
(1, 'CASH', 'Money in raw form', 1, '1647351646', 1),
(2, 'Bank Transfer', 'test', 1, '1647351681', 1),
(3, 'RTGS', '', 1, '1647351724', 1),
(6, 'cheque', 'dghgjgdj', 0, '1703828966', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_additions`
--

CREATE TABLE `payroll_additions` (
  `pay_add_id` int(11) NOT NULL,
  `pay_entry_id` int(11) NOT NULL,
  `add_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_additions`
--

INSERT INTO `payroll_additions` (`pay_add_id`, `pay_entry_id`, `add_id`) VALUES
(1, 2, 2),
(2, 3, 1),
(3, 2, 1),
(4, 8, 3),
(5, 8, 2),
(6, 9, 3),
(7, 9, 2),
(8, 10, 3),
(9, 11, 3),
(10, 12, 2),
(11, 13, 2);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_deductions`
--

CREATE TABLE `payroll_deductions` (
  `pay_deduct_id` int(11) NOT NULL,
  `pay_entry_id` int(11) NOT NULL,
  `deduct_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_deductions`
--

INSERT INTO `payroll_deductions` (`pay_deduct_id`, `pay_entry_id`, `deduct_id`) VALUES
(1, 2, 2),
(3, 3, 2),
(6, 8, 2),
(7, 9, 2),
(8, 10, 3),
(9, 10, 2),
(10, 10, 1),
(11, 11, 2),
(12, 12, 3),
(13, 13, 3),
(14, 13, 2);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_entry`
--

CREATE TABLE `payroll_entry` (
  `pay_entry_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_from` date NOT NULL,
  `payment_to` date NOT NULL,
  `processed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_entry`
--

INSERT INTO `payroll_entry` (`pay_entry_id`, `name`, `payment_date`, `payment_from`, `payment_to`, `processed`) VALUES
(2, 'Jan salary', '2022-04-28', '2022-01-01', '2022-01-31', 1),
(3, 'Feb salary', '2022-04-28', '2022-02-01', '2022-02-28', 1),
(8, 'QBS Support', '2023-12-21', '2023-12-22', '2023-12-31', 1),
(12, 'QBS Support', '2024-01-13', '2024-01-12', '2024-01-14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_process`
--

CREATE TABLE `payroll_process` (
  `pay_proc_id` int(11) NOT NULL,
  `pay_entry_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `total_w_hours` mediumint(9) NOT NULL,
  `total_ot_hours` mediumint(9) NOT NULL,
  `w_hr_salary` decimal(14,2) NOT NULL,
  `ot_hr_salary` decimal(14,2) NOT NULL,
  `gross_pay` decimal(14,2) NOT NULL,
  `total_deductions` decimal(14,2) NOT NULL,
  `total_additions` decimal(14,2) NOT NULL,
  `net_pay` decimal(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_process`
--

INSERT INTO `payroll_process` (`pay_proc_id`, `pay_entry_id`, `employee_id`, `total_w_hours`, `total_ot_hours`, `w_hr_salary`, `ot_hr_salary`, `gross_pay`, `total_deductions`, `total_additions`, `net_pay`) VALUES
(1, 2, 1, 0, 0, '0.00', '0.00', '0.00', '1000.00', '2000.00', '1000.00'),
(2, 2, 2, 0, 0, '0.00', '0.00', '0.00', '1000.00', '2000.00', '1000.00'),
(3, 2, 3, 0, 0, '0.00', '0.00', '0.00', '1000.00', '2000.00', '1000.00'),
(4, 3, 1, 0, 0, '0.00', '0.00', '0.00', '1000.00', '2000.00', '1000.00'),
(5, 3, 2, 0, 0, '0.00', '0.00', '0.00', '1000.00', '2000.00', '1000.00'),
(6, 3, 3, 0, 0, '0.00', '0.00', '0.00', '1000.00', '2000.00', '1000.00'),
(7, 3, 4, 0, 0, '0.00', '0.00', '0.00', '1000.00', '2000.00', '1000.00'),
(8, 3, 5, 0, 0, '0.00', '0.00', '0.00', '1000.00', '2000.00', '1000.00'),
(9, 3, 8, 0, 0, '0.00', '0.00', '0.00', '1000.00', '2000.00', '1000.00'),
(10, 8, 1, 0, 0, '0.00', '0.00', '0.00', '1000.00', '5.00', '-995.00'),
(11, 8, 2, 0, 0, '0.00', '0.00', '0.00', '1000.00', '5.00', '-995.00'),
(12, 8, 3, 0, 0, '0.00', '0.00', '0.00', '1000.00', '5.00', '-995.00'),
(13, 8, 4, 0, 0, '0.00', '0.00', '0.00', '1000.00', '5.00', '-995.00'),
(14, 8, 5, 0, 0, '0.00', '0.00', '0.00', '1000.00', '5.00', '-995.00'),
(15, 8, 8, 0, 0, '0.00', '0.00', '0.00', '1000.00', '5.00', '-995.00'),
(16, 11, 1, 0, 0, '0.00', '0.00', '0.00', '1000.00', '5.00', '-995.00'),
(17, 11, 2, 0, 0, '0.00', '0.00', '0.00', '1000.00', '5.00', '-995.00'),
(18, 11, 3, 0, 0, '0.00', '0.00', '0.00', '1000.00', '5.00', '-995.00'),
(19, 11, 4, 0, 0, '0.00', '0.00', '0.00', '1000.00', '5.00', '-995.00'),
(20, 11, 9, 0, 0, '0.00', '0.00', '0.00', '1000.00', '5.00', '-995.00'),
(21, 12, 13, 0, 0, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
(22, 12, 14, 0, 0, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
(23, 12, 15, 0, 0, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
(24, 12, 16, 0, 0, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
(25, 13, 13, 8, 2, '41680000.00', '110880.00', '41790880.00', '142963400.00', '4168000.00', '-97004520.00'),
(26, 13, 14, 0, 0, '0.00', '0.00', '0.00', '1000.00', '0.00', '-1000.00'),
(27, 13, 15, 0, 0, '0.00', '0.00', '0.00', '1000.00', '0.00', '-1000.00'),
(28, 13, 16, 0, 0, '0.00', '0.00', '0.00', '1000.00', '0.00', '-1000.00');

-- --------------------------------------------------------

--
-- Table structure for table `planning`
--

CREATE TABLE `planning` (
  `planning_id` int(11) NOT NULL,
  `costing_id` int(255) NOT NULL,
  `finished_good_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `finished_date` date DEFAULT NULL,
  `price_id` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `planning`
--

INSERT INTO `planning` (`planning_id`, `costing_id`, `finished_good_id`, `start_date`, `end_date`, `finished_date`, `price_id`, `stock`, `status`, `created_by`) VALUES
(1, 1, 25, '2024-02-22', '2024-02-29', '0000-00-00', 1, 1, 2, 1),
(13, 0, 26, '2024-12-12', '2024-12-22', '2024-12-14', 1, 1, 0, 1),
(14, 0, 22, '2024-12-12', '2024-12-10', '2024-12-15', 2, 1, 0, 1),
(17, 0, 23, '2024-12-14', '2024-12-31', '2024-12-31', 6, 150, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `price_list`
--

CREATE TABLE `price_list` (
  `price_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `tax1` tinyint(4) NOT NULL,
  `tax2` tinyint(4) NOT NULL,
  `description` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `price_list`
--

INSERT INTO `price_list` (`price_id`, `name`, `amount`, `tax1`, `tax2`, `description`, `active`, `created_at`, `created_by`) VALUES
(1, 'Amount 1', '2000.00', 1, 3, '', 1, '1645602972', 1),
(2, 'Amount 2', '3000.00', 3, 1, '', 1, '1645603028', 1),
(6, 'amount 3', '8000.00', 1, 1, 'hi', 1, '1703658950', 1),
(8, 'Amount 4', '120.00', 4, 3, 'dsghsdh', 1, '1704864693', 1);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `budget` decimal(14,2) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `units` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `name`, `cust_id`, `related_to`, `related_id`, `start_date`, `end_date`, `budget`, `description`, `status`, `type`, `units`, `type_id`, `address`, `city`, `state`, `country`, `zipcode`, `created_at`, `created_by`) VALUES
(23, 'TEst', 37, 'finished_good', 21, '2024-01-18', '2024-02-10', '10000.00', 'asdf', 2, 1, 0, 0, '', '', '', '', '', '1705490992', 1),
(24, 'Thamizharasi', 45, 'finished_good', 21, '2024-01-18', '2024-01-19', '11221401.00', 'eafgeghef', 1, 1, 0, 0, '', '', '', '', '', '1705752813', 1),
(25, 'Production ', 45, 'finished_good', 21, '2024-01-23', '2024-01-30', '11221401.00', 'sdgdfhdddgfsfdahgm', 1, 1, 0, 0, '', '', '', '', '', '1705916085', 1);

-- --------------------------------------------------------

--
-- Table structure for table `project_amenity`
--

CREATE TABLE `project_amenity` (
  `project_amen_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `amenity_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_amenity`
--

INSERT INTO `project_amenity` (`project_amen_id`, `project_id`, `amenity_id`) VALUES
(1, 3, 3),
(2, 3, 2),
(3, 3, 1),
(4, 5, 3),
(5, 6, 2),
(6, 7, 2);

-- --------------------------------------------------------

--
-- Table structure for table `project_expense`
--

CREATE TABLE `project_expense` (
  `expense_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `expense_date` date NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `receipt` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_expense`
--

INSERT INTO `project_expense` (`expense_id`, `project_id`, `name`, `expense_date`, `amount`, `payment_id`, `receipt`, `description`) VALUES
(3, 3, 'test', '2022-05-03', '2000.00', 1, '', ''),
(4, 3, 'kumar', '2023-12-16', '34.35', 1, 'export(1)_4.xlsx', 'test'),
(5, 3, 'kumar', '2023-12-16', '34.35', 1, 'export(1)_5.xlsx', 'test'),
(6, 3, 'kumar', '2023-12-16', '34.35', 1, 'export(1)_6.xlsx', 'test'),
(14, 24, 'Thamizharasi', '2024-01-19', '8000.00', 2, 'export(49)_7.csv', 'eshtg');

-- --------------------------------------------------------

--
-- Table structure for table `project_members`
--

CREATE TABLE `project_members` (
  `project_mem_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_members`
--

INSERT INTO `project_members` (`project_mem_id`, `project_id`, `member_id`) VALUES
(1, 1, 3),
(2, 1, 2),
(3, 1, 1),
(4, 2, 3),
(5, 2, 2),
(6, 2, 1),
(7, 3, 3),
(8, 3, 2),
(9, 3, 1),
(10, 5, 2),
(11, 6, 2),
(12, 7, 1),
(13, 8, 2),
(14, 9, 3),
(15, 9, 2),
(16, 9, 1),
(17, 10, 3),
(18, 10, 2),
(19, 10, 1),
(20, 11, 1),
(21, 12, 2),
(22, 19, 2),
(23, 20, 3),
(24, 21, 2),
(25, 22, 3),
(26, 23, 1),
(27, 24, 2),
(28, 25, 2);

-- --------------------------------------------------------

--
-- Table structure for table `project_phase`
--

CREATE TABLE `project_phase` (
  `phase_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `started` tinyint(4) NOT NULL DEFAULT 0,
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_phase`
--

INSERT INTO `project_phase` (`phase_id`, `name`, `started`, `project_id`) VALUES
(0, 'step one eat 5 star', 1, 23),
(3, 'Phase1', 1, 3),
(15, 'test', 1, 2),
(16, 'Domain', 1, 2),
(17, 'QBS Support', 1, 2),
(18, 'test', 1, 2),
(22, 'test', 1, 24);

-- --------------------------------------------------------

--
-- Table structure for table `project_rawmaterials`
--

CREATE TABLE `project_rawmaterials` (
  `project_raw_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `req_qty` int(11) NOT NULL,
  `req_for_dispatch` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_rawmaterials`
--

INSERT INTO `project_rawmaterials` (`project_raw_id`, `related_to`, `related_id`, `project_id`, `req_qty`, `req_for_dispatch`) VALUES
(6, 'raw_material', 2, 3, 5, 0),
(7, 'raw_material', 16, 3, 20, 0),
(17, 'raw_material', 47, 2, 2, 0),
(18, 'raw_material', 21, 2, 20, 0),
(19, 'raw_material', 3, 2, 78, 0);

-- --------------------------------------------------------

--
-- Table structure for table `project_testing`
--

CREATE TABLE `project_testing` (
  `project_test_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `complete_before` date NOT NULL,
  `result` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` date NOT NULL,
  `description` text NOT NULL,
  `remarks` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_workgroup`
--

CREATE TABLE `project_workgroup` (
  `project_wgrp_id` int(11) NOT NULL,
  `phase_id` int(11) NOT NULL,
  `wgroup_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `started_at` varchar(40) NOT NULL,
  `completed_at` varchar(40) NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `fetched` tinyint(1) NOT NULL DEFAULT 0,
  `worker_type` varchar(60) NOT NULL,
  `contractor_id` int(11) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `paid_till` decimal(14,2) NOT NULL,
  `pay_before` varchar(10) NOT NULL,
  `c_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_workgroup`
--

INSERT INTO `project_workgroup` (`project_wgrp_id`, `phase_id`, `wgroup_id`, `team_id`, `project_id`, `started_at`, `completed_at`, `completed`, `fetched`, `worker_type`, `contractor_id`, `amount`, `paid_till`, `pay_before`, `c_status`) VALUES
(1, 1, 1, 1, 1, '2022-05-02', '2022-05-03', 1, 1, '', 0, '0.00', '0.00', '', 0),
(2, 1, 2, 2, 1, '2022-05-03', '2023-12-27', 1, 1, '', 0, '0.00', '0.00', '', 0),
(3, 1, 3, 2, 1, '2023-12-27', '2023-12-27', 1, 0, '', 0, '0.00', '0.00', '', 0),
(4, 2, 1, 1, 1, '2022-05-02', '2023-12-27', 1, 1, '', 0, '0.00', '0.00', '', 0),
(7, 3, 1, 0, 3, '2022-05-04', '2022-05-04', 1, 1, 'Contractor', 3, '30000.00', '3000.00', '2022-06-11', 1),
(12, 6, 1, 1, 8, '2023-12-16', '2023-12-16', 1, 0, 'Team', 0, '0.00', '0.00', '', 0),
(13, 7, 2, 2, 9, '', '', 0, 0, 'Team', 0, '0.00', '0.00', '', 0),
(14, 7, 10, 2, 9, '', '', 0, 0, 'Team', 0, '0.00', '0.00', '', 0),
(15, 9, 2, 2, 10, '', '', 0, 0, 'Team', 0, '0.00', '0.00', '', 0),
(16, 10, 1, 1, 11, '2023-12-26', '2023-12-26', 1, 1, 'Team', 0, '0.00', '0.00', '', 0),
(17, 11, 1, 1, 11, '2023-12-26', '', 0, 1, 'Team', 0, '0.00', '0.00', '', 0),
(18, 12, 1, 1, 12, '', '', 0, 0, 'Team', 0, '0.00', '0.00', '', 0),
(20, 16, 3, 15, 2, '2024-01-02', '', 0, 1, 'Team', 0, '0.00', '0.00', '', 0),
(21, 17, 3, 15, 2, '2024-01-02', '', 0, 1, 'Team', 0, '0.00', '0.00', '', 0),
(22, 18, 10, 15, 2, '2024-01-02', '', 0, 1, 'Team', 0, '0.00', '0.00', '', 0),
(24, 19, 2, 15, 22, '2024-01-11', '2024-01-11', 1, 1, 'Team', 0, '0.00', '0.00', '', 0),
(25, 20, 2, 15, 22, '2024-01-11', '2024-01-11', 1, 1, 'Team', 0, '0.00', '0.00', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `property_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `units` smallint(6) NOT NULL,
  `type_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `construct_start` varchar(20) NOT NULL,
  `construct_end` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`property_id`, `name`, `units`, `type_id`, `address`, `city`, `state`, `country`, `zipcode`, `construct_start`, `construct_end`, `status`, `description`, `created_at`, `created_by`) VALUES
(4, 'Rainbow', 1, 2, 'No.164, First Floor, Arcot Rd, Valasaravakkam', 'Chennai', 'Tamil Nadu', 'India', '600087', '', '', 1, '', '1648643990', 1),
(7, 'QBS Support', 0, 1, 'Test Address', 'chennai', 'Tamil Nadu', 'India', '600087', '2023-12-13', '2023-12-30', 1, '', '1702386459', 1);

-- --------------------------------------------------------

--
-- Table structure for table `propertytype`
--

CREATE TABLE `propertytype` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(140) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `propertytype`
--

INSERT INTO `propertytype` (`type_id`, `type_name`, `created_at`, `created_by`) VALUES
(0, 'mouse', '1714202419', 1),
(1, 'Flat', '1648623239', 1),
(2, 'Villa', '1648623248', 1),
(6, 'shop', '1705904222', 1);

-- --------------------------------------------------------

--
-- Table structure for table `property_amenity`
--

CREATE TABLE `property_amenity` (
  `prop_ament_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `amenity_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_amenity`
--

INSERT INTO `property_amenity` (`prop_ament_id`, `property_id`, `amenity_id`) VALUES
(4, 4, 3),
(5, 4, 2),
(6, 4, 1),
(7, 3, 3),
(8, 3, 2),
(9, 3, 1),
(10, 7, 3),
(11, 8, 1),
(12, 9, 3),
(13, 10, 2),
(14, 11, 2),
(15, 12, 2),
(16, 13, 2);

-- --------------------------------------------------------

--
-- Table structure for table `property_unit`
--

CREATE TABLE `property_unit` (
  `prop_unit_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `unit_name` varchar(140) NOT NULL,
  `floor_no` varchar(80) NOT NULL,
  `area_sqft` varchar(30) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `price` decimal(14,2) NOT NULL,
  `tax1` int(11) NOT NULL,
  `tax2` int(11) NOT NULL,
  `direction` varchar(140) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_unit`
--

INSERT INTO `property_unit` (`prop_unit_id`, `property_id`, `unit_name`, `floor_no`, `area_sqft`, `status`, `price`, `tax1`, `tax2`, `direction`, `description`) VALUES
(1, 1, 'FLAT123454545', '1', '2500', 1, '3200000.00', 1, 2, '', ''),
(2, 2, 'FLAT124234234234234234234234', '2', '2500', 1, '3000000.00', 3, 0, '', ''),
(3, 4, 'FLAT125', '1', '2500', 1, '4500000.00', 1, 2, 'South', 'hello'),
(4, 4, 'QBS Support', '76', '454', 0, '12.23', 1, 2, 'h', ''),
(5, 5, 'siva', '76', '454', 0, '12.23', 1, 2, 'h', ''),
(6, 4, 'Sevensanja', '12', '212122', 0, '545.55', 3, 2, '87', ''),
(7, 7, 'sevens', '76', '2121.22', 0, '545.55', 1, 1, 'h', '');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_invoice`
--

CREATE TABLE `purchase_invoice` (
  `invoice_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `amount` decimal(14,2) NOT NULL,
  `paid_till` decimal(14,2) NOT NULL,
  `grn_updated` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_invoice`
--

INSERT INTO `purchase_invoice` (`invoice_id`, `order_id`, `status`, `amount`, `paid_till`, `grn_updated`) VALUES
(1, 1, 3, '20000.00', '20000.00', 0),
(2, 2, 0, '24000.00', '0.00', 0),
(3, 3, 0, '96000.00', '0.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE `purchase_order` (
  `order_id` int(11) NOT NULL,
  `order_code` varchar(140) NOT NULL,
  `req_id` int(11) NOT NULL,
  `selection_rule` varchar(140) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `internal_transport` tinyint(1) NOT NULL DEFAULT 0,
  `transport_id` int(11) NOT NULL,
  `transport_unit` int(11) NOT NULL,
  `transport_charge` decimal(14,2) NOT NULL,
  `rfq_id` int(11) NOT NULL,
  `supp_location_id` int(11) NOT NULL,
  `delivery_date` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `warehouse_id` int(11) NOT NULL,
  `terms_condition` text NOT NULL,
  `notes` text NOT NULL,
  `grn_created` tinyint(1) NOT NULL DEFAULT 0,
  `invoice_created` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order`
--

INSERT INTO `purchase_order` (`order_id`, `order_code`, `req_id`, `selection_rule`, `supplier_id`, `internal_transport`, `transport_id`, `transport_unit`, `transport_charge`, `rfq_id`, `supp_location_id`, `delivery_date`, `status`, `warehouse_id`, `terms_condition`, `notes`, `grn_created`, `invoice_created`, `created_at`, `created_by`) VALUES
(1, 'RFQ_ORDER - 1', 1, '3', 4, 0, 0, 0, '0.00', 0, 5, '2024-01-09', 2, 1, 'Test', 'Test', 1, 1, '1704777683', 1),
(2, 'QWE14', 5, '3', 4, 0, 0, 0, '0.00', 0, 5, '2024-01-12', 2, 1, 'test', 'test', 0, 1, '1705053527', 1),
(3, 'CO01', 6, '3', 4, 0, 0, 0, '0.00', 0, 5, '2024-01-13', 2, 1, 'test', 'test', 1, 1, '1705056235', 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `received_qty` int(11) NOT NULL,
  `returned_qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`order_item_id`, `order_id`, `related_to`, `related_id`, `price_id`, `quantity`, `received_qty`, `returned_qty`) VALUES
(1, 1, 'raw_material', 43, 1, 5, 3, 2),
(2, 1, 'raw_material', 53, 1, 5, 3, 2),
(3, 2, 'raw_material', 47, 1, 12, 0, 0),
(4, 3, 'semi_finished', 17, 6, 12, 1, 11);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_payments`
--

CREATE TABLE `purchase_payments` (
  `purchase_pay_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `paid_on` date NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_payments`
--

INSERT INTO `purchase_payments` (`purchase_pay_id`, `invoice_id`, `payment_id`, `amount`, `paid_on`, `transaction_id`, `notes`) VALUES
(4, 1, 1, '20000.00', '2022-03-18', '', 'Advance payment'),
(5, 1, 1, '2000.00', '2022-03-17', '', 'first off'),
(6, 2, 1, '1000.00', '2023-12-27', 'RFT-1234', 'nothing'),
(7, 2, 2, '100000.00', '2023-12-29', '6538796879', '987'),
(8, 3, 1, '1000.00', '2024-01-02', 'TEST TEST', 'Nothing'),
(9, 1, 1, '20000.00', '2024-01-13', '1234567890', 'fdgsagsdr3e r');

-- --------------------------------------------------------

--
-- Table structure for table `push_notify`
--

CREATE TABLE `push_notify` (
  `push_id` int(11) NOT NULL,
  `notify_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `fetched` tinyint(1) NOT NULL DEFAULT 0,
  `pushed_at` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `push_notify`
--

INSERT INTO `push_notify` (`push_id`, `notify_id`, `to_id`, `fetched`, `pushed_at`) VALUES
(1, 5, 1, 0, '12');

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `quote_id` int(11) NOT NULL,
  `code` varchar(140) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `quote_date` date NOT NULL,
  `cust_id` int(11) NOT NULL,
  `shippingaddr_id` int(11) NOT NULL,
  `billingaddr_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `currency_place` varchar(255) NOT NULL,
  `transport_req` tinyint(1) NOT NULL DEFAULT 0,
  `trans_charge` decimal(14,2) NOT NULL,
  `discount` decimal(14,2) NOT NULL,
  `terms_condition` text NOT NULL,
  `payment_terms` varchar(140) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`quote_id`, `code`, `subject`, `expiry_date`, `quote_date`, `cust_id`, `shippingaddr_id`, `billingaddr_id`, `currency_id`, `currency_place`, `transport_req`, `trans_charge`, `discount`, `terms_condition`, `payment_terms`, `status`, `created_at`, `created_by`) VALUES
(1, 'PRO-001', 'test', '2024-02-28', '2024-02-20', 48, 0, 0, 8, 'after', 0, '0.00', '141.00', '<p>fda</p>', '1 day', 4, '1708425326', 1),
(2, 'PRO-002', 'order', '2024-05-04', '2024-04-27', 45, 0, 0, 8, 'before', 1, '2700.00', '0.00', '<p>mnmmm,mkmuummbki9lfdkguibnvbi</p>', 'demo', 4, '1714201029', 1);

-- --------------------------------------------------------

--
-- Table structure for table `raw_materials`
--

CREATE TABLE `raw_materials` (
  `raw_material_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `short_desc` varchar(1000) NOT NULL,
  `long_desc` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `code` varchar(140) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raw_materials`
--

INSERT INTO `raw_materials` (`raw_material_id`, `name`, `short_desc`, `long_desc`, `group_id`, `code`, `unit_id`, `brand_id`, `created_at`, `created_by`) VALUES
(42, 'Pens', 'test', 'test34', 9, '78768768', 1, 2, '1702365934', 1),
(43, 'Grape', 'no', 'no\r\n', 10, '2345', 1, 1, '1702365979', 1),
(45, 'Raj', 'sdfsdf', 'dfgsdfg', 9, '1517', 2, 2, '1703418703', 1),
(47, 'test2', 'wsera', 'rhyseht', 9, 'c58554', 2, 2, '1703829990', 1),
(50, 'IGST', '', '', 9, '15', 2, 5, '1703830282', 1),
(53, 'vegetabl', 'fsf', 'fsdf', 9, 'JHJ', 1, 2, '1703852159', 1),
(56, 'test123', 'wearghedth', 'esraghedth', 9, 'b650745', 1, 1, '1704178435', 1),
(58, 'Thamizh', 'swgreg', 'wegfdg', 10, 'cfseh', 1, 2, '1704189142', 1),
(60, 'dhssdfzkhc', 'dhtaezt', 'erheazd', 9, 'dfxhjn6', 1, 1, '1704191459', 1),
(66, 'TEST EMAIL', 'esgrseg', 'aer', 9, 'SO128', 1, 1, '1704800020', 1),
(68, 'test', 'sfgag', 'asdgasg', 9, 'SO128dfs', 2, 1, '1705664411', 1),
(70, 'fdhseh', 'xfgsjg', 'fgjsjsrfgjsg\r\n', 10, '876', 2, 2, '1705750892', 1);

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `request_id` int(11) NOT NULL,
  `from_m` varchar(140) NOT NULL,
  `to_m` varchar(140) NOT NULL,
  `purpose` varchar(140) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `mail_request` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `requested_at` varchar(20) NOT NULL,
  `requested_by` int(11) NOT NULL,
  `responded_by` int(11) NOT NULL,
  `responded_at` varchar(20) NOT NULL,
  `created_at` varchar(50) NOT NULL,
  `updated_at` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`request_id`, `from_m`, `to_m`, `purpose`, `related_to`, `related_id`, `description`, `mail_request`, `status`, `requested_at`, `requested_by`, `responded_by`, `responded_at`, `created_at`, `updated_at`) VALUES
(1, 'CRM', 'Sales', 'Order Request', 'customer', 9, 'Want audi car', 1, 0, '1645877705', 1, 0, '1646477095', '', ''),
(2, 'CRM', 'Sales', 'Order Request', 'customer', 8, 'Test', 0, 0, '1646023067', 1, 0, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `requisition`
--

CREATE TABLE `requisition` (
  `req_id` int(11) NOT NULL,
  `req_code` varchar(140) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `priority` tinyint(1) NOT NULL,
  `mail_sent` tinyint(1) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `remarks` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requisition`
--

INSERT INTO `requisition` (`req_id`, `req_code`, `assigned_to`, `status`, `priority`, `mail_sent`, `description`, `remarks`, `created_at`, `created_by`) VALUES
(1, 'REQ -1', 1, 2, 1, 0, 'Raw materials', 'arrpoved', '1704777210', 1),
(2, 'USAP1234', 2, 0, 3, 1, 'test', '', '1704970233', 1),
(5, 'USAP12345', 1, 2, 2, 0, 'dfsdfgfg', 'ttt', '1705053250', 1),
(6, 'CR01', 1, 2, 2, 0, 'test', 'test', '1705054691', 1),
(7, '12345', 1, 0, 0, 0, 'test', '', '1710413255', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rfq`
--

CREATE TABLE `rfq` (
  `rfq_id` int(11) NOT NULL,
  `rfq_code` varchar(140) NOT NULL,
  `req_id` int(11) NOT NULL,
  `expiry_date` date NOT NULL,
  `terms_condition` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rfq`
--

INSERT INTO `rfq` (`rfq_id`, `rfq_code`, `req_id`, `expiry_date`, `terms_condition`, `created_at`, `created_by`, `status`) VALUES
(2, 'RFQ1436', 6, '2024-01-26', 'dsagseg', '1705752467', 1, 1),
(3, 'RFQ1435', 1, '2024-04-28', 'uvuv', '1714203870', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sale_invoice`
--

CREATE TABLE `sale_invoice` (
  `invoice_id` int(11) NOT NULL,
  `code` varchar(140) NOT NULL,
  `invoice_date` date NOT NULL,
  `invoice_expiry` date NOT NULL,
  `cust_id` int(11) NOT NULL,
  `shippingaddr_id` int(11) NOT NULL,
  `billingaddr_id` varchar(50) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `currency_place` varchar(255) NOT NULL,
  `transport_req` tinyint(1) NOT NULL DEFAULT 0,
  `trans_charge` decimal(14,2) NOT NULL,
  `discount` decimal(14,2) NOT NULL,
  `terms_condition` text NOT NULL,
  `payment_terms` varchar(140) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `remarks` text NOT NULL,
  `paid_till` decimal(14,2) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `can_edit` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_invoice`
--

INSERT INTO `sale_invoice` (`invoice_id`, `code`, `invoice_date`, `invoice_expiry`, `cust_id`, `shippingaddr_id`, `billingaddr_id`, `currency_id`, `currency_place`, `transport_req`, `trans_charge`, `discount`, `terms_condition`, `payment_terms`, `status`, `remarks`, `paid_till`, `type`, `can_edit`, `created_at`, `created_by`) VALUES
(1, 'INV-2024001', '2024-02-24', '2024-03-02', 38, 0, '', 6, 'after', 0, '100.00', '10.00', '<p>fsf</p>', 'fdsf', 2, '', '591.20', 1, 0, '1708768579', 1),
(19, 'INV-2024002', '2024-06-10', '2024-06-17', 37, 0, '', 8, 'before', 0, '0.00', '0.00', '', 'expense add', 2, '', '59000.00', 3, 0, '1718020598', 1),
(20, 'INV-2024020', '2024-06-10', '2024-06-17', 37, 0, '', 6, 'after', 0, '0.00', '0.00', '', 'last', 2, '', '6350.00', 3, 0, '1718027512', 1),
(21, 'INV-2024021', '2024-06-11', '2024-06-18', 37, 0, '', 11, 'after', 0, '0.00', '0.00', '', 'last', 2, '', '4750.00', 3, 0, '1718089327', 1),
(22, 'INV-2024022', '2024-11-02', '2024-11-09', 45, 0, '', 6, 'after', 0, '0.00', '1000.00', '', 'test', 0, '', '0.00', 1, 1, '1730548926', 1),
(23, 'INV-2024023', '2024-11-18', '2024-11-25', 38, 0, '', 6, 'after', 0, '11.00', '0.00', '<p>the value&nbsp;</p>', 'syfdsg', 0, '', '0.00', 3, 0, '1731916765', 1),
(24, 'INV-2024024', '2024-12-09', '2024-12-16', 37, 0, '', 6, 'after', 0, '0.00', '0.00', '<p>aaaa</p>', 'syfdsg', 0, '', '0.00', 1, 1, '1733740865', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sale_order`
--

CREATE TABLE `sale_order` (
  `order_id` int(11) NOT NULL,
  `code` varchar(140) NOT NULL,
  `order_date` date NOT NULL,
  `order_expiry` date NOT NULL,
  `cust_id` int(11) NOT NULL,
  `shippingaddr_id` int(11) NOT NULL,
  `transport_req` tinyint(1) NOT NULL DEFAULT 0,
  `trans_charge` decimal(14,2) NOT NULL,
  `discount` decimal(14,2) NOT NULL,
  `terms_condition` text NOT NULL,
  `payment_terms` varchar(140) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `remarks` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `can_edit` tinyint(1) NOT NULL DEFAULT 1,
  `stock_pick` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_order`
--

INSERT INTO `sale_order` (`order_id`, `code`, `order_date`, `order_expiry`, `cust_id`, `shippingaddr_id`, `transport_req`, `trans_charge`, `discount`, `terms_condition`, `payment_terms`, `status`, `remarks`, `type`, `can_edit`, `stock_pick`, `created_at`, `created_by`) VALUES
(1, 'ORD-001', '2024-02-06', '2024-02-13', 37, 3, 1, '20.00', '0.00', '<h2><i><strong>fdsf</strong></i></h2>', 'fsdf', 0, '', 1, 1, 0, '1707201992', 1),
(2, 'ORD-002', '2024-02-08', '2024-02-15', 37, 3, 0, '0.00', '0.00', '<p>test</p>', 'test', 0, '', 1, 1, 0, '1707367424', 1),
(3, 'ORD-003', '2024-02-08', '2024-02-15', 38, 0, 1, '0.00', '0.00', '<p>dsf</p>', 'test', 0, '', 1, 1, 0, '1707367498', 1),
(4, 'ORD-004', '2024-02-08', '2024-02-15', 46, 0, 1, '0.00', '0.00', '<p>dsf</p>', 'df', 0, '', 1, 1, 0, '1707371230', 1),
(5, 'ORD-005', '2024-02-08', '2024-02-15', 38, 0, 1, '10.00', '10.00', '<p>fds</p>', 'dsf', 0, '', 1, 1, 0, '1707371271', 1),
(6, 'ORD-006', '2024-02-08', '2024-02-15', 37, 0, 0, '0.00', '0.00', '<p>sdf</p>', 'fsd', 0, '', 1, 1, 0, '1707371306', 1),
(7, 'ORD-007', '2024-02-14', '2024-02-21', 45, 0, 0, '500.00', '0.00', '<p>rerserfdf</p>', '1 day', 0, '', 1, 1, 0, '1707918086', 1),
(8, 'ORD-008', '2024-02-15', '2024-02-22', 38, 0, 0, '0.00', '10.00', '<p>test</p>', 'asdf', 0, '', 1, 1, 0, '1707999377', 1),
(9, 'ORD-5', '2024-02-20', '2024-02-22', 48, 0, 0, '0.00', '141.00', '<p>fda</p>', '1 day', 0, '', 1, 0, 0, '1708508832', 1),
(10, '1234567890', '2024-04-27', '2024-06-30', 45, 0, 1, '2700.00', '0.00', '<p>mnmmm,mkmuummbki9lfdkguibnvbi</p>', 'demo', 0, '', 1, 0, 0, '1717592008', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sale_order_items`
--

CREATE TABLE `sale_order_items` (
  `sale_item_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `quote_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(14,2) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `tax1` tinyint(4) NOT NULL,
  `tax2` tinyint(4) NOT NULL,
  `send_qty` int(11) NOT NULL,
  `return_qty` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_order_items`
--

INSERT INTO `sale_order_items` (`sale_item_id`, `related_to`, `related_id`, `price_id`, `quote_id`, `order_id`, `invoice_id`, `quantity`, `unit_price`, `amount`, `tax1`, `tax2`, `send_qty`, `return_qty`, `timestamp`) VALUES
(2, 'finished_good', 25, 8, 0, 0, 4, 1, '120.00', '120.00', 9, 18, 0, 0, '2024-02-06 12:27:02'),
(3, 'finished_good', 22, 2, 0, 0, 4, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-06 12:27:02'),
(4, 'finished_good', 22, 2, 0, 0, 5, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-06 13:28:36'),
(5, 'finished_good', 23, 1, 0, 0, 5, 2, '2000.00', '4000.00', 9, 18, 0, 0, '2024-02-06 13:29:04'),
(6, 'finished_good', 22, 2, 0, 2, 0, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-08 04:43:44'),
(7, 'finished_good', 23, 1, 0, 3, 0, 1, '2000.00', '2000.00', 9, 18, 0, 0, '2024-02-08 04:44:58'),
(8, 'finished_good', 21, 1, 0, 4, 0, 1, '2000.00', '2000.00', 9, 18, 0, 0, '2024-02-08 05:47:10'),
(9, 'finished_good', 22, 2, 0, 5, 0, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-08 05:47:51'),
(10, 'finished_good', 25, 8, 0, 6, 0, 2, '120.00', '240.00', 20, 18, 0, 0, '2024-02-08 05:48:26'),
(11, 'finished_good', 25, 8, 0, 0, 6, 1, '120.00', '120.00', 20, 18, 0, 0, '2024-02-09 05:09:29'),
(13, 'finished_good', 25, 8, 0, 0, 7, 1, '120.00', '120.00', 20, 18, 0, 0, '2024-02-09 05:38:41'),
(15, 'finished_good', 22, 2, 0, 0, 7, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-09 05:46:12'),
(16, 'finished_good', 21, 1, 0, 0, 6, 1, '2000.00', '2000.00', 9, 18, 0, 0, '2024-02-09 05:46:35'),
(17, 'finished_good', 22, 2, 5, 0, 0, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-09 05:50:24'),
(18, 'finished_good', 22, 2, 0, 0, 8, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-14 13:03:15'),
(19, 'finished_good', 22, 2, 6, 0, 0, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-14 13:36:23'),
(20, 'finished_good', 25, 8, 6, 0, 0, 1, '120.00', '120.00', 20, 18, 0, 0, '2024-02-14 13:36:23'),
(21, 'finished_good', 23, 1, 0, 7, 0, 1, '2000.00', '2000.00', 9, 18, 0, 0, '2024-02-14 13:41:26'),
(22, 'finished_good', 21, 1, 0, 7, 0, 2, '2000.00', '4000.00', 9, 18, 0, 0, '2024-02-14 13:41:26'),
(23, 'finished_good', 23, 1, 0, 5, 0, 1, '2000.00', '2000.00', 9, 18, 0, 0, '2024-02-14 13:42:44'),
(24, 'finished_good', 22, 2, 7, 0, 0, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-15 11:06:08'),
(25, 'finished_good', 25, 8, 7, 0, 0, 1, '120.00', '120.00', 20, 18, 0, 0, '2024-02-15 11:06:08'),
(27, 'finished_good', 25, 8, 0, 8, 0, 1, '120.00', '120.00', 20, 18, 0, 0, '2024-02-15 12:16:17'),
(28, 'finished_good', 22, 2, 0, 8, 0, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-15 12:16:17'),
(29, 'finished_good', 22, 2, 8, 0, 0, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-19 07:38:21'),
(30, 'finished_good', 25, 8, 8, 0, 0, 1, '120.00', '120.00', 20, 18, 0, 0, '2024-02-19 07:38:22'),
(31, 'finished_good', 22, 2, 20, 0, 0, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-19 09:44:35'),
(35, 'finished_good', 22, 2, 29, 0, 0, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-19 11:17:49'),
(38, 'finished_good', 25, 8, 30, 0, 0, 1, '120.00', '120.00', 20, 18, 0, 0, '2024-02-20 10:30:50'),
(39, 'finished_good', 22, 2, 30, 0, 0, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-20 10:33:13'),
(40, 'finished_good', 25, 8, 31, 0, 0, 1, '120.00', '120.00', 20, 18, 0, 0, '2024-02-20 10:34:18'),
(41, 'finished_good', 25, 8, 1, 9, 0, 1, '120.00', '120.00', 20, 18, 0, 0, '2024-02-20 10:35:26'),
(42, 'finished_good', 22, 2, 1, 9, 0, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-20 10:36:14'),
(45, 'finished_good', 22, 2, 0, 0, 9, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-21 07:15:35'),
(47, 'finished_good', 23, 1, 0, 0, 10, 1, '2000.00', '2000.00', 9, 18, 0, 0, '2024-02-21 08:40:57'),
(48, 'finished_good', 21, 1, 0, 0, 10, 1, '2000.00', '2000.00', 9, 18, 0, 0, '2024-02-21 08:52:54'),
(49, 'finished_good', 22, 2, 0, 0, 11, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-22 11:05:46'),
(50, 'finished_good', 25, 8, 0, 0, 11, 2, '120.00', '240.00', 20, 18, 0, 0, '2024-02-22 11:05:46'),
(51, 'finished_good', 25, 8, 0, 0, 12, 1, '120.00', '120.00', 20, 18, 0, 0, '2024-02-24 07:59:16'),
(52, 'finished_good', 25, 8, 0, 0, 1, 1, '120.00', '120.00', 20, 18, 0, 0, '2024-02-24 09:56:19'),
(53, 'finished_good', 22, 2, 0, 0, 2, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-02-24 12:41:39'),
(54, 'finished_good', 25, 8, 0, 0, 2, 1, '120.00', '120.00', 20, 18, 0, 0, '2024-02-24 12:41:40'),
(55, 'finished_good', 22, 2, 2, 10, 0, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-04-27 06:57:09'),
(56, 'finished_good', 25, 8, 0, 0, 3, 44, '120.00', '5280.00', 20, 18, 0, 0, '2024-06-10 05:09:26'),
(57, 'finished_good', 22, 2, 0, 0, 16, 1, '3000.00', '3000.00', 18, 9, 0, 0, '2024-06-10 10:44:07'),
(58, 'finished_good', 21, 1, 0, 0, 22, 5, '2000.00', '10000.00', 9, 18, 0, 0, '2024-11-02 12:02:06'),
(59, 'finished_good', 23, 8, 0, 0, 22, 69, '120.00', '8280.00', 20, 18, 0, 0, '2024-11-02 12:02:06'),
(60, 'finished_good', 25, 8, 0, 0, 22, 20, '120.00', '2400.00', 20, 18, 0, 0, '2024-11-02 12:02:06'),
(61, 'finished_good', 25, 8, 0, 0, 24, 1, '120.00', '120.00', 20, 18, 0, 0, '2024-12-09 10:41:05');

-- --------------------------------------------------------

--
-- Table structure for table `sale_payments`
--

CREATE TABLE `sale_payments` (
  `sale_pay_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `paid_on` date NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_payments`
--

INSERT INTO `sale_payments` (`sale_pay_id`, `invoice_id`, `payment_id`, `amount`, `paid_on`, `transaction_id`, `notes`) VALUES
(1, 1, 2, '10.00', '2024-02-24', 'test123', 'test'),
(8, 19, 1, '54000.00', '2024-06-15', '', ''),
(9, 19, 1, '5000.00', '2024-06-06', '', ''),
(10, 20, 1, '4000.00', '2024-06-10', '', ''),
(11, 20, 1, '2350.00', '2024-06-11', '', ''),
(12, 21, 1, '4750.00', '2024-06-11', '', ''),
(13, 1, 1, '245.60', '2024-11-29', 'test', ''),
(14, 1, 1, '245.60', '2024-11-29', 'test', '');

-- --------------------------------------------------------

--
-- Table structure for table `scheduling`
--

CREATE TABLE `scheduling` (
  `scheduling_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scheduling`
--

INSERT INTO `scheduling` (`scheduling_id`, `start_date`, `due_date`, `location`, `service_id`) VALUES
(2, '2024-01-28', '2024-01-31', 'address ', 4),
(4, '2024-01-19', '2024-01-21', 'test', 5);

-- --------------------------------------------------------

--
-- Table structure for table `scrap`
--

CREATE TABLE `scrap` (
  `scrap_id` int(255) NOT NULL,
  `item_code` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(25) NOT NULL,
  `rate` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selection_rule`
--

CREATE TABLE `selection_rule` (
  `rule_id` int(11) NOT NULL,
  `rule_name` varchar(140) NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `selection_rule`
--

INSERT INTO `selection_rule` (`rule_id`, `rule_name`, `description`, `created_at`, `created_by`) VALUES
(3, 'rule 1', 'Test rule', '1646907504', 1),
(8, 'test', 'rtet', '1705909175', 1);

-- --------------------------------------------------------

--
-- Table structure for table `selection_rule_segment`
--

CREATE TABLE `selection_rule_segment` (
  `rule_seg_id` int(11) NOT NULL,
  `rule_id` int(11) NOT NULL,
  `segment_id` int(11) NOT NULL,
  `segment_value_idx` tinyint(4) NOT NULL,
  `above_below` tinyint(1) NOT NULL DEFAULT 0,
  `exclude` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `selection_rule_segment`
--

INSERT INTO `selection_rule_segment` (`rule_seg_id`, `rule_id`, `segment_id`, `segment_value_idx`, `above_below`, `exclude`) VALUES
(3, 3, 1, 1, 1, 0),
(4, 3, 2, 2, 1, 0),
(5, 3, 3, 1, 1, 0),
(17, 8, 2, 3, 1, 1),
(18, 8, 3, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `semi_finished`
--

CREATE TABLE `semi_finished` (
  `semi_finished_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `short_desc` varchar(1000) NOT NULL,
  `long_desc` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `code` varchar(140) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semi_finished`
--

INSERT INTO `semi_finished` (`semi_finished_id`, `name`, `short_desc`, `long_desc`, `group_id`, `code`, `unit_id`, `brand_id`, `created_at`, `created_by`) VALUES
(1, 'test ', 'sadsdsdasdsad', 'asssssssssssssssss', 12, '76679', 1, 1, '1733994626', 1),
(2, 'test 22', 'aaa', '', 12, '766792', 1, 2, '1733994745', 1);

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `service_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `priority` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `assigned_to` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `service_desc` text NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`service_id`, `code`, `name`, `priority`, `status`, `assigned_to`, `employee_id`, `service_desc`, `created_by`) VALUES
(4, 'SA03', 'test', 1, 0, 3, 14, 'test', 1),
(5, 'SA04', 'Qbs Support', 3, 2, 1, 13, 'test', 1),
(6, 'dsgsd', 'test', 1, 0, 2, 14, 'sdfhsh', 1),
(7, 'fdhs574', 'test', 1, 0, 2, 13, 'edfgdfhdf', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `stock_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`stock_id`, `related_to`, `related_id`, `warehouse_id`, `quantity`, `price_id`, `timestamp`) VALUES
(1, 'finished_good', 25, 1, 100, 1, '2024-12-14 07:29:51'),
(12, 'raw_material', 2, 15, 0, 1, '2024-01-17 10:39:23'),
(13, 'raw_material', 16, 8, 0, 1, '2024-01-17 10:39:23'),
(14, 'finished_good', 21, 1, 1, 1, '2024-01-17 10:39:23'),
(15, 'semi_finished', 17, 4, 1, 2, '2024-01-17 10:39:23'),
(17, 'semi_finished', 17, 11, 0, 2, '2024-01-17 10:39:23'),
(18, 'finished_good', 22, 6, 1, 2, '2024-01-17 10:39:23'),
(19, 'finished_good', 25, 11, 1, 8, '2024-01-17 10:39:23'),
(20, 'finished_good', 21, 1, 1, 8, '2024-01-17 10:39:23'),
(21, 'finished_good', 21, 4, 1, 1, '2024-01-17 10:39:23'),
(22, 'semi_finished', 18, 2, 1, 1, '2024-01-17 10:39:23'),
(23, 'finished_good', 25, 1, 1, 8, '2024-01-17 10:39:23'),
(24, 'finished_good', 21, 2, 0, 8, '2024-01-20 11:54:33'),
(25, 'semi_finished', 18, 1, 0, 1, '2024-01-20 11:54:44'),
(26, 'finished_good', 21, 6, 0, 8, '2024-01-20 11:55:00'),
(27, 'finished_good', 21, 6, 1, 1, '2024-01-20 11:55:24'),
(28, 'raw_material', 50, 1, 1, 1, '2024-01-20 12:04:54'),
(29, 'finished_good', 21, 2, 1, 1, '2024-01-20 12:07:11'),
(30, 'semi_finished', 17, 1, 1, 2, '2024-01-20 12:50:23'),
(31, 'raw_material', 50, 9, 1, 1, '2024-01-22 05:25:45'),
(32, 'finished_good', 23, 1, 1, 1, '2024-01-22 05:31:30'),
(33, 'raw_material', 47, 2, 1, 1, '2024-01-22 07:51:43'),
(34, 'finished_good', 25, 1, 100, 1, '2024-12-14 12:48:26'),
(35, 'finished_good', 25, 1, 100, 1, '2024-12-14 12:50:57'),
(36, 'finished_good', 25, 1, 100, 1, '2024-12-14 13:08:54');

-- --------------------------------------------------------

--
-- Table structure for table `stock_alerts`
--

CREATE TABLE `stock_alerts` (
  `stock_alert_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `alert_qty_level` int(11) NOT NULL,
  `alert_before` tinyint(4) NOT NULL,
  `recurring` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_alerts`
--

INSERT INTO `stock_alerts` (`stock_alert_id`, `related_to`, `related_id`, `alert_qty_level`, `alert_before`, `recurring`) VALUES
(2, 'raw_material', 2, 30, 20, 1),
(3, 'semi_finished', 2, 45, 127, 1),
(4, 'finished_good', 2, 30, 20, 0),
(5, 'raw_material', 24, 324, 76, 1),
(6, 'finished_good', 7, 324, 76, 1),
(7, 'raw_material', 17, 10000, 2, 1),
(9, 'raw_material', 45, 324, 76, 1),
(12, 'semi_finished', 12, 20, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `stock_entry`
--

CREATE TABLE `stock_entry` (
  `stock_entry_id` int(11) NOT NULL,
  `entry_type` tinyint(1) NOT NULL DEFAULT 0,
  `stock_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `entry_log` varchar(1000) NOT NULL,
  `order_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_entry`
--

INSERT INTO `stock_entry` (`stock_entry_id`, `entry_type`, `stock_id`, `qty`, `entry_log`, `order_id`, `project_id`, `created_at`) VALUES
(3, 0, 1, 8, '', 0, 0, '2022-03-22'),
(4, 0, 2, 10, '', 0, 0, '2022-03-22'),
(5, 1, 0, 1, '', 0, 0, '2022-04-04'),
(6, 1, 0, 1, '', 0, 0, '2022-04-04'),
(7, 1, 9, 1, '', 0, 0, '2022-04-12'),
(22, 2, 9, 1, '', 1, 0, '2022-04-13'),
(23, 2, 10, 1, '', 1, 0, '2022-04-13'),
(37, 3, 7, 5, '', 0, 1, ''),
(41, 3, 8, 5, '', 0, 1, '2022-05-03'),
(42, 3, 2, 3, '', 0, 1, '2022-05-03'),
(43, 3, 8, 2, '', 0, 1, '2023-12-16'),
(44, 3, 2, 15, '', 0, 1, '2023-12-16'),
(45, 1, 0, 1, '', 0, 0, '2024-01-02'),
(46, 1, 0, 1, '', 0, 0, '2024-01-03'),
(47, 1, 0, 1, '', 0, 0, '2024-01-03'),
(48, 1, 0, 1, '', 0, 0, '2024-01-04'),
(49, 1, 0, 1, '', 0, 0, '2024-01-10'),
(50, 1, 0, 1, '', 0, 0, '2024-01-10'),
(51, 1, 0, 1, '', 0, 0, '2024-01-13'),
(52, 1, 0, 1, '', 0, 0, '2024-01-13'),
(53, 1, 14, 1, '', 0, 0, '2024-01-20'),
(54, 1, 21, 1, '', 0, 0, '2024-01-20'),
(55, 1, 0, 1, '', 0, 0, '2024-01-20'),
(56, 1, 14, 1, '', 0, 0, '2024-01-20'),
(57, 1, 0, 1, '', 0, 0, '2024-01-20'),
(58, 1, 0, 1, '', 0, 0, '2024-01-22'),
(59, 1, 0, 1, '', 0, 0, '2024-01-22'),
(60, 1, 0, 1, '', 0, 0, '2024-01-22');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `code` varchar(20) NOT NULL,
  `position` varchar(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `office_number` varchar(13) NOT NULL,
  `fax_number` varchar(13) NOT NULL,
  `company` varchar(140) NOT NULL,
  `gst` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `website` varchar(255) NOT NULL,
  `payment_terms` text NOT NULL,
  `description` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `source_id`, `name`, `code`, `position`, `email`, `phone`, `office_number`, `fax_number`, `company`, `gst`, `address`, `city`, `state`, `country`, `zipcode`, `website`, `payment_terms`, `description`, `active`, `created_at`, `created_by`) VALUES
(4, 1, 'Production Thamizharasi', 'SO128', 'web developer', 'support@qbrainstorm.com', '1234567890', '', '', 'qbs', '5754654466689898', 'madurai', 'Thiruvallur', 'tamilnadu', 'India', '602024', '', '', '', 0, '1704187140', 1),
(6, 1, 'QBS Support', 'werewr', '434', 'suppor9t@qbrainstorm.com', '9080780700', 'werwer', 'werewr', 'qbrainstorm', 'rer', 'Test Address', 'chennai', 'Tamil Nadu', 'India', '600087', 'wer', 'werwer', 'werwer', 1, '1705909217', 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_contacts`
--

CREATE TABLE `supplier_contacts` (
  `contact_id` int(11) NOT NULL,
  `firstname` varchar(140) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `position` varchar(140) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_contacts`
--

INSERT INTO `supplier_contacts` (`contact_id`, `firstname`, `lastname`, `email`, `phone`, `active`, `position`, `supplier_id`, `created_at`, `created_by`) VALUES
(10, 'QBSsss', 'Support', 'supporttr@qbrainstorm.comf', '9080780700', 1, 'werwer', 4, '1702126664', 1),
(13, 'delete', 'Support', 'support@qbrainstorm.com', '9080780700', 1, 'Purchase Managers', 40, '1702208458', 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_locations`
--

CREATE TABLE `supplier_locations` (
  `location_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_locations`
--

INSERT INTO `supplier_locations` (`location_id`, `address`, `city`, `state`, `country`, `zipcode`, `supplier_id`, `created_at`, `created_by`) VALUES
(7, 'Delete Address', 'chennai', 'Tamil Nadu', 'India', '600087', 40, '1702208450', 1),
(15, 'chennai', 'Thiruvallur', 'tamilnadu', 'India', '602024', 4, '1705907980', 1),
(16, 'chennai', 'Thiruvallur', 'tamilnadu', 'India', '602024', 4, '1705907987', 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_rfq`
--

CREATE TABLE `supplier_rfq` (
  `supp_rfq_id` int(11) NOT NULL,
  `rfq_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `selection_rule` varchar(140) NOT NULL,
  `mail_status` tinyint(1) NOT NULL DEFAULT 0,
  `send_contacts` tinyint(1) NOT NULL DEFAULT 0,
  `include_attach` tinyint(1) NOT NULL DEFAULT 0,
  `responded` tinyint(1) NOT NULL DEFAULT 0,
  `responded_at` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_rfq`
--

INSERT INTO `supplier_rfq` (`supp_rfq_id`, `rfq_id`, `supplier_id`, `selection_rule`, `mail_status`, `send_contacts`, `include_attach`, `responded`, `responded_at`) VALUES
(0, 2, 6, '8', 0, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_segments`
--

CREATE TABLE `supplier_segments` (
  `segment_id` int(11) NOT NULL,
  `segment_key` varchar(140) NOT NULL,
  `segment_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_segments`
--

INSERT INTO `supplier_segments` (`segment_id`, `segment_key`, `segment_value`, `created_at`, `created_by`) VALUES
(2, 'Product Quality', '{\"0\":\"Not Applicable\",\"1\":\"Poor\",\"2\":\"Average\",\"3\":\"Standard\"}', '1646312456', 1),
(3, 'Support', '{\"0\":\"Not Applicable\",\"1\":\"Poor\",\"2\":\"Good\",\"3\":\"Average\",\"4\":\"Excellent\"}', '1646910701', 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_segment_map`
--

CREATE TABLE `supplier_segment_map` (
  `supp_seg_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `segment_json` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_segment_map`
--

INSERT INTO `supplier_segment_map` (`supp_seg_id`, `supplier_id`, `segment_json`) VALUES
(3, 7, '{\"1\":\"2\",\"2\":\"2\",\"3\":\"1\"}'),
(4, 4, '{\"1\":\"1\",\"2\":\"1\",\"3\":\"1\"}'),
(5, 6, '{\"1\":\"3\",\"2\":\"2\",\"3\":\"0\"}'),
(8, 40, '{\"1\":\"3\",\"2\":\"1\",\"3\":\"0\"}');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_sources`
--

CREATE TABLE `supplier_sources` (
  `source_id` int(11) NOT NULL,
  `source_name` varchar(140) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_sources`
--

INSERT INTO `supplier_sources` (`source_id`, `source_name`, `created_at`, `created_by`) VALUES
(1, 'Email campaign', '1646283489', 1),
(8, 'General', '1705908706', 1);

-- --------------------------------------------------------

--
-- Table structure for table `supply_list`
--

CREATE TABLE `supply_list` (
  `supply_list_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `supply_qty` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supply_list`
--

INSERT INTO `supply_list` (`supply_list_id`, `related_to`, `related_id`, `supplier_id`, `supply_qty`, `created_at`, `created_by`) VALUES
(0, 'raw_material', 43, 6, 0, '1714647088', 1),
(4, 'raw_material', 17, 4, 0, '1647066656', 1),
(19, 'semi_finished', 6, 40, 0, '1702208413', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `related_to` varchar(20) NOT NULL,
  `related_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `assignees` int(11) NOT NULL,
  `followers` int(11) NOT NULL,
  `task_description` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `name`, `status`, `start_date`, `due_date`, `related_to`, `related_id`, `priority`, `assignees`, `followers`, `task_description`, `created_by`) VALUES
(2, 'Follow the lead', 0, '2024-01-06', '2024-01-11', 'lead', 4, 1, 1, 2, 'Follow the lead', 1),
(3, 'QBS Support', 3, '2024-01-06', '2024-01-24', 'project', 3, 0, 1, 3, 'test', 1),
(4, 'Domain C', 1, '2024-01-07', '2024-01-13', 'ticket', 10, 2, 2, 3, 'test two', 1);

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `tax_id` int(11) NOT NULL,
  `tax_name` varchar(50) NOT NULL,
  `percent` tinyint(4) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `taxes`
--

INSERT INTO `taxes` (`tax_id`, `tax_name`, `percent`, `created_at`, `created_by`) VALUES
(1, 'CGST', 9, '1645106126', 1),
(2, 'SGST', 9, '1645158708', 1),
(3, 'IGST', 18, '1645159286', 1),
(4, 'HGST', 20, '1703321477', 1);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `description` text NOT NULL,
  `team_count` smallint(6) NOT NULL,
  `lead_by` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`team_id`, `name`, `description`, `team_count`, `lead_by`, `created_at`, `created_by`) VALUES
(15, 'test', 'wsrhht', 5, 2, '1704173024', 1);

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `member_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`member_id`, `team_id`, `employee_id`) VALUES
(3, 1, 1),
(4, 1, 2),
(5, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int(11) NOT NULL,
  `subject` varchar(140) NOT NULL,
  `priority` tinyint(1) NOT NULL DEFAULT 0,
  `cust_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `problem` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `remarks` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`ticket_id`, `subject`, `priority`, `cust_id`, `project_id`, `assigned_to`, `problem`, `status`, `remarks`, `created_at`, `created_by`) VALUES
(1, 'Testing purpose', 1, 21, 23, 3, 'aaaaa', 2, '', '1733226897', 1),
(2, 'Testing purpose', 2, 21, 23, 15, 'aaa', 1, '', '1733227310', 1),
(3, 'testing', 3, 21, 23, 15, 'no problem', 0, '', '1733304148', 1),
(4, 'Testing purpose', 3, 21, 23, 3, 'solving', 2, '', '1733319330', 1),
(11, 'For Flash Data View', 0, 21, 23, 0, 'message', 2, '', '1733466923', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_comment`
--

CREATE TABLE `ticket_comment` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `related_id` int(11) NOT NULL,
  `related_type` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_comment`
--

INSERT INTO `ticket_comment` (`id`, `ticket_id`, `related_id`, `related_type`, `comment`, `created_at`) VALUES
(1, 1, 21, 'client', 'This In this project i want clear explanation.', '2024-12-05 13:14:11'),
(2, 1, 1, 'staff', 'This is for demo purpose\nthe\nsd', '2024-12-05 15:49:30'),
(3, 1, 1, 'staff', 'This is from staff side', '2024-12-05 18:16:26'),
(4, 1, 21, 'client', 'This is from client side', '2024-12-05 18:18:22'),
(5, 1, 21, 'client', 'Today is Friday', '2024-12-06 10:38:52'),
(6, 1, 21, 'client', 'Nature is a breathtaking tapestry of vibrant colors, serene landscapes, and captivating wildlife. It offers a tranquil escape from the chaos of daily life, allowing us to reconnect with the world around us. From lush green forests to the rhythmic waves of the ocean, every element tells a unique story. Let’s cherish and preserve this incredible gift for generations to come.', '2024-12-06 10:40:35'),
(7, 11, 21, 'client', 'Test', '2024-12-06 17:45:26');

-- --------------------------------------------------------

--
-- Table structure for table `timesheet`
--

CREATE TABLE `timesheet` (
  `id` int(11) NOT NULL,
  `related_to` text DEFAULT NULL,
  `related_url` text DEFAULT NULL,
  `start_timer` datetime NOT NULL,
  `end_timer` datetime NOT NULL,
  `note` longtext NOT NULL,
  `time_taken` text DEFAULT NULL,
  `assigned_by` int(100) NOT NULL,
  `assigned_to` int(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timesheet`
--

INSERT INTO `timesheet` (`id`, `related_to`, `related_url`, `start_timer`, `end_timer`, `note`, `time_taken`, `assigned_by`, `assigned_to`, `created_at`, `updated_at`) VALUES
(30, 'Follow the lead', 'http://192.168.29.3/Erp/public/erp/crm/task-view/2', '2024-11-15 17:34:01', '2024-11-15 17:35:25', 'test', ' Minutes 1', 1, 1, '2024-11-15 17:34:01', NULL),
(31, 'Follow the lead', 'http://192.168.29.3/Erp/public/erp/crm/task-view/2', '2024-11-15 18:51:45', '2024-11-15 18:52:06', 'test', '', 1, 1, '2024-11-15 18:51:45', NULL),
(32, 'Follow the lead', 'http://192.168.29.3/Erp/public/erp/crm/task-view/2', '2024-11-15 18:52:21', '2024-11-19 11:44:22', 'aaaaa', '', 1, 1, '2024-11-15 18:52:21', NULL),
(33, NULL, NULL, '2024-11-19 11:44:37', '2024-11-19 11:44:49', '', NULL, 1, 1, '2024-11-19 11:44:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transports`
--

CREATE TABLE `transports` (
  `transport_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `type_id` int(11) NOT NULL,
  `code` varchar(140) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `delivery_count` int(11) NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transports`
--

INSERT INTO `transports` (`transport_id`, `name`, `type_id`, `code`, `active`, `status`, `delivery_count`, `description`, `created_at`, `created_by`) VALUES
(1, 'test2', 1, '123', 1, 1, 0, 'hello', '1647087322', 1),
(2, 'test', 3, '124', 1, 1, 0, 'hellow', '1647087414', 1),
(6, 'Leo', 3, '234234', 1, 1, 0, 'test', '1703409406', 1),
(7, 'Q Brainstorm', 3, '6969', 1, 1, 0, 'test', '1703661598', 1),
(9, 'test', 3, 'c58554', 1, 0, 0, 'dfhssdh', '1705743126', 1),
(10, 'Thamizharasi', 12, 'b6501', 1, 0, 0, 'sdyhrthsrh', '1705743269', 1),
(11, 'kkkkkk', 1, 'werewr', 1, 0, 0, 'wqewqe', '1705743352', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transport_type`
--

CREATE TABLE `transport_type` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(140) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transport_type`
--

INSERT INTO `transport_type` (`type_id`, `type_name`, `created_at`, `created_by`) VALUES
(1, 'Airways', '1647082077', 1),
(3, 'Roadways', '1647328672', 1),
(11, 'shipway', '1705743195', 1),
(12, 'Lorry', '1705743222', 1),
(14, 'Bike', '1705743244', 1);

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `unit_id` int(11) NOT NULL,
  `unit_name` varchar(20) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`unit_id`, `unit_name`, `created_at`, `created_by`) VALUES
(1, 'Kg', '1645537313', 1),
(2, 'Litter', '1702460196', 1),
(5, 'gram', '1704864231', 1);

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `warehouse_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zipcode` varchar(20) NOT NULL,
  `has_bins` tinyint(1) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `aisle_count` int(11) NOT NULL,
  `racks_per_aisle` int(11) NOT NULL,
  `shelf_per_rack` int(11) NOT NULL,
  `bins_per_shelf` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`warehouse_id`, `name`, `address`, `city`, `state`, `country`, `zipcode`, `has_bins`, `description`, `aisle_count`, `racks_per_aisle`, `shelf_per_rack`, `bins_per_shelf`) VALUES
(1, 'Qbrainstorm Software', 'No.164, First Floor, Arcot Rd, Valasaravakkam', 'madurai', 'tamilnadu', 'India', '602024', 0, 'dhshfa', 0, 0, 0, 0),
(2, 'LOC-2', 'No.164, First Floor, Arcot Rd, Valasaravakkam', 'Chennai', 'Tamil Nadu', 'India', '600087', 0, '', 0, 0, 0, 0),
(4, 'vinitha', 'chennai', 'Thiruvallur', 'tamilnadu', 'India', '602024', 0, '', 0, 0, 0, 0),
(6, 'sample6', 'chennai', 'Thiruvallur', 'tamilnadu', 'India', '602024', 1, 'zsvjksv', 45, 45, 55, 55),
(8, 'sadhana', 'chennai', 'Thiruvallur', 'tamilnadu', 'India', '602024', 1, 'ftjyguj', 44, 547, 635, 5),
(9, 'harina', 'chennai', 'Thiruvallur', 'tamilnadu', 'India', '602024', 1, 'ftjyguj', 44, 547, 635, 5),
(11, 'fathima', 'chennai', 'Thiruvallur', 'tamilnadu', 'India', '602024', 1, 'sfffrg', 54, 45, 45, 54);

-- --------------------------------------------------------

--
-- Table structure for table `workstation`
--

CREATE TABLE `workstation` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `workstationtype_id` bigint(20) NOT NULL,
  `warehose_id` bigint(20) NOT NULL,
  `electricity_cost` bigint(20) NOT NULL,
  `rent_cost` bigint(20) NOT NULL,
  `consumable_cost` bigint(20) NOT NULL,
  `wages_cost` bigint(20) NOT NULL,
  `work_hour_start` time DEFAULT NULL,
  `work_hour_end` time DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workstation`
--

INSERT INTO `workstation` (`id`, `name`, `workstationtype_id`, `warehose_id`, `electricity_cost`, `rent_cost`, `consumable_cost`, `wages_cost`, `work_hour_start`, `work_hour_end`, `description`, `status`, `created_at`, `updated_at`) VALUES
(6, 'Test case 2', 1, 1, 120, 500, 250, 800, '10:00:00', '19:30:00', 'This is test case 2 for deleting operation..!', 0, '2024-12-12 11:14:27', '2024-12-12 11:14:27'),
(7, 'Baking ', 1, 4, 100, 567, 240, 800, '10:00:00', '18:30:00', 'This is demo purpose..!', 3, '2024-12-12 11:20:35', '2024-12-12 11:20:35');

-- --------------------------------------------------------

--
-- Table structure for table `workstationtype`
--

CREATE TABLE `workstationtype` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `electricity_cost` bigint(20) NOT NULL,
  `rent_cost` bigint(20) NOT NULL,
  `consumable_cost` bigint(20) NOT NULL,
  `wages_cost` bigint(20) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workstationtype`
--

INSERT INTO `workstationtype` (`id`, `name`, `electricity_cost`, `rent_cost`, `consumable_cost`, `wages_cost`, `description`, `created_at`, `updated_at`) VALUES
(1, 'test', 100, 567, 240, 800, '', '2024-12-11 12:20:01', '2024-12-11 12:20:01'),
(3, 'new one', 12, 22, 23, 66, 'demo...', '2024-12-11 15:25:30', '2024-12-11 15:25:30');

-- --------------------------------------------------------

--
-- Table structure for table `work_groups`
--

CREATE TABLE `work_groups` (
  `wgroup_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `approx_days` smallint(6) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_groups`
--

INSERT INTO `work_groups` (`wgroup_id`, `name`, `approx_days`, `description`) VALUES
(2, 'Group 2', 50, 'hello'),
(3, 'Group 3', 120, 'hello'),
(10, 'QBS Support', 32767, 'test');

-- --------------------------------------------------------

--
-- Table structure for table `work_group_equip`
--

CREATE TABLE `work_group_equip` (
  `wgroup_equip_id` int(11) NOT NULL,
  `wgroup_id` int(11) NOT NULL,
  `equip_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_group_equip`
--

INSERT INTO `work_group_equip` (`wgroup_equip_id`, `wgroup_id`, `equip_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(9, 10, 1),
(10, 11, 1),
(11, 12, 1),
(12, 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `work_group_items`
--

CREATE TABLE `work_group_items` (
  `wgroup_item_id` int(11) NOT NULL,
  `wgroup_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_group_items`
--

INSERT INTO `work_group_items` (`wgroup_item_id`, `wgroup_id`, `related_to`, `related_id`, `qty`) VALUES
(1, 1, 'raw_material', 2, 5),
(5, 3, 'raw_material', 21, 10),
(6, 10, 'raw_material', 3, 78),
(7, 11, 'raw_material', 20, 59),
(9, 12, 'raw_material', 47, 1),
(10, 2, 'raw_material', 43, 78),
(11, 13, 'raw_material', 53, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accountbase`
--
ALTER TABLE `accountbase`
  ADD PRIMARY KEY (`base_id`);

--
-- Indexes for table `account_groups`
--
ALTER TABLE `account_groups`
  ADD PRIMARY KEY (`acc_group_id`);

--
-- Indexes for table `additions`
--
ALTER TABLE `additions`
  ADD PRIMARY KEY (`add_id`);

--
-- Indexes for table `amenity`
--
ALTER TABLE `amenity`
  ADD PRIMARY KEY (`amenity_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcementid`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`attach_id`);

--
-- Indexes for table `auto_transaction`
--
ALTER TABLE `auto_transaction`
  ADD PRIMARY KEY (`autotrans_id`);

--
-- Indexes for table `auto_trans_list`
--
ALTER TABLE `auto_trans_list`
  ADD PRIMARY KEY (`trans_id`);

--
-- Indexes for table `bankaccounts`
--
ALTER TABLE `bankaccounts`
  ADD PRIMARY KEY (`bank_id`);

--
-- Indexes for table `bom`
--
ALTER TABLE `bom`
  ADD PRIMARY KEY (`bom_id`);

--
-- Indexes for table `bom_items`
--
ALTER TABLE `bom_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bom_operation`
--
ALTER TABLE `bom_operation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bom_operation_list`
--
ALTER TABLE `bom_operation_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bom_scrap_items`
--
ALTER TABLE `bom_scrap_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `calender_events`
--
ALTER TABLE `calender_events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `contractors`
--
ALTER TABLE `contractors`
  ADD PRIMARY KEY (`contractor_id`);

--
-- Indexes for table `contractor_payments`
--
ALTER TABLE `contractor_payments`
  ADD PRIMARY KEY (`contractor_pay_id`);

--
-- Indexes for table `contracttype`
--
ALTER TABLE `contracttype`
  ADD PRIMARY KEY (`cont_id`);

--
-- Indexes for table `contract_commend`
--
ALTER TABLE `contract_commend`
  ADD PRIMARY KEY (`discussion_id`);

--
-- Indexes for table `contract_renewals`
--
ALTER TABLE `contract_renewals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contract_tasks`
--
ALTER TABLE `contract_tasks`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `costing`
--
ALTER TABLE `costing`
  ADD PRIMARY KEY (`costing_id`);

--
-- Indexes for table `credits_applied`
--
ALTER TABLE `credits_applied`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credit_items`
--
ALTER TABLE `credit_items`
  ADD PRIMARY KEY (`credit_item_id`);

--
-- Indexes for table `credit_notes`
--
ALTER TABLE `credit_notes`
  ADD PRIMARY KEY (`credit_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`currency_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`cust_id`);

--
-- Indexes for table `customer_billingaddr`
--
ALTER TABLE `customer_billingaddr`
  ADD PRIMARY KEY (`billingaddr_id`);

--
-- Indexes for table `customer_contacts`
--
ALTER TABLE `customer_contacts`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `customer_shippingaddr`
--
ALTER TABLE `customer_shippingaddr`
  ADD PRIMARY KEY (`shippingaddr_id`);

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD PRIMARY KEY (`cf_id`);

--
-- Indexes for table `custom_field_values`
--
ALTER TABLE `custom_field_values`
  ADD PRIMARY KEY (`cfv_id`);

--
-- Indexes for table `deductions`
--
ALTER TABLE `deductions`
  ADD PRIMARY KEY (`deduct_id`);

--
-- Indexes for table `delivery_records`
--
ALTER TABLE `delivery_records`
  ADD PRIMARY KEY (`delivery_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `designation`
--
ALTER TABLE `designation`
  ADD PRIMARY KEY (`designation_id`);

--
-- Indexes for table `dispatch`
--
ALTER TABLE `dispatch`
  ADD PRIMARY KEY (`dispatch_id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `emp_attendance`
--
ALTER TABLE `emp_attendance`
  ADD PRIMARY KEY (`attend_id`);

--
-- Indexes for table `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`equip_id`);

--
-- Indexes for table `erpcontract`
--
ALTER TABLE `erpcontract`
  ADD PRIMARY KEY (`contract_id`);

--
-- Indexes for table `erpexpenses`
--
ALTER TABLE `erpexpenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clientid` (`clientid`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `category` (`category`),
  ADD KEY `currency` (`currency`);

--
-- Indexes for table `erp_companyinfo`
--
ALTER TABLE `erp_companyinfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `erp_expenses_categories`
--
ALTER TABLE `erp_expenses_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `erp_goals`
--
ALTER TABLE `erp_goals`
  ADD PRIMARY KEY (`goals_id`);

--
-- Indexes for table `erp_groups`
--
ALTER TABLE `erp_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `erp_groups_map`
--
ALTER TABLE `erp_groups_map`
  ADD PRIMARY KEY (`groupmap_id`);

--
-- Indexes for table `erp_jobqueue`
--
ALTER TABLE `erp_jobqueue`
  ADD PRIMARY KEY (`job_id`);

--
-- Indexes for table `erp_log`
--
ALTER TABLE `erp_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `erp_roles`
--
ALTER TABLE `erp_roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `erp_settings`
--
ALTER TABLE `erp_settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `erp_users`
--
ALTER TABLE `erp_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `estimates`
--
ALTER TABLE `estimates`
  ADD PRIMARY KEY (`estimate_id`);

--
-- Indexes for table `estimate_items`
--
ALTER TABLE `estimate_items`
  ADD PRIMARY KEY (`est_item_id`);

--
-- Indexes for table `expense_items`
--
ALTER TABLE `expense_items`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `expense_task`
--
ALTER TABLE `expense_task`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `finished_goods`
--
ALTER TABLE `finished_goods`
  ADD PRIMARY KEY (`finished_good_id`);

--
-- Indexes for table `general_ledger`
--
ALTER TABLE `general_ledger`
  ADD PRIMARY KEY (`ledger_id`);

--
-- Indexes for table `gl_accounts`
--
ALTER TABLE `gl_accounts`
  ADD PRIMARY KEY (`gl_acc_id`);

--
-- Indexes for table `grn`
--
ALTER TABLE `grn`
  ADD PRIMARY KEY (`grn_id`);

--
-- Indexes for table `inventory_requisition`
--
ALTER TABLE `inventory_requisition`
  ADD PRIMARY KEY (`invent_req_id`);

--
-- Indexes for table `inventory_services`
--
ALTER TABLE `inventory_services`
  ADD PRIMARY KEY (`invent_service_id`);

--
-- Indexes for table `inventory_warehouse`
--
ALTER TABLE `inventory_warehouse`
  ADD PRIMARY KEY (`invent_house_id`);

--
-- Indexes for table `journal_entry`
--
ALTER TABLE `journal_entry`
  ADD PRIMARY KEY (`journal_id`);

--
-- Indexes for table `knowledgebase_groups`
--
ALTER TABLE `knowledgebase_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `knowledge_base`
--
ALTER TABLE `knowledge_base`
  ADD PRIMARY KEY (`article_id`);

--
-- Indexes for table `knowledge_base_feedback`
--
ALTER TABLE `knowledge_base_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`lead_id`);

--
-- Indexes for table `lead_source`
--
ALTER TABLE `lead_source`
  ADD PRIMARY KEY (`source_id`);

--
-- Indexes for table `marketing`
--
ALTER TABLE `marketing`
  ADD PRIMARY KEY (`marketing_id`);

--
-- Indexes for table `master`
--
ALTER TABLE `master`
  ADD PRIMARY KEY (`master_id`);

--
-- Indexes for table `mrp_bom`
--
ALTER TABLE `mrp_bom`
  ADD PRIMARY KEY (`bom_id`);

--
-- Indexes for table `mrp_dataset_forecast`
--
ALTER TABLE `mrp_dataset_forecast`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mrp_scheduling`
--
ALTER TABLE `mrp_scheduling`
  ADD PRIMARY KEY (`mrp_scheduling_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notify_id`);

--
-- Indexes for table `packs`
--
ALTER TABLE `packs`
  ADD PRIMARY KEY (`pack_id`);

--
-- Indexes for table `pack_records`
--
ALTER TABLE `pack_records`
  ADD PRIMARY KEY (`pack_rec_id`);

--
-- Indexes for table `pack_unit`
--
ALTER TABLE `pack_unit`
  ADD PRIMARY KEY (`pack_unit_id`);

--
-- Indexes for table `payment_modes`
--
ALTER TABLE `payment_modes`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `payroll_additions`
--
ALTER TABLE `payroll_additions`
  ADD PRIMARY KEY (`pay_add_id`);

--
-- Indexes for table `payroll_deductions`
--
ALTER TABLE `payroll_deductions`
  ADD PRIMARY KEY (`pay_deduct_id`);

--
-- Indexes for table `payroll_entry`
--
ALTER TABLE `payroll_entry`
  ADD PRIMARY KEY (`pay_entry_id`);

--
-- Indexes for table `payroll_process`
--
ALTER TABLE `payroll_process`
  ADD PRIMARY KEY (`pay_proc_id`);

--
-- Indexes for table `planning`
--
ALTER TABLE `planning`
  ADD PRIMARY KEY (`planning_id`);

--
-- Indexes for table `price_list`
--
ALTER TABLE `price_list`
  ADD PRIMARY KEY (`price_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `project_amenity`
--
ALTER TABLE `project_amenity`
  ADD PRIMARY KEY (`project_amen_id`);

--
-- Indexes for table `project_expense`
--
ALTER TABLE `project_expense`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `project_members`
--
ALTER TABLE `project_members`
  ADD PRIMARY KEY (`project_mem_id`);

--
-- Indexes for table `project_phase`
--
ALTER TABLE `project_phase`
  ADD PRIMARY KEY (`phase_id`);

--
-- Indexes for table `project_rawmaterials`
--
ALTER TABLE `project_rawmaterials`
  ADD PRIMARY KEY (`project_raw_id`);

--
-- Indexes for table `project_testing`
--
ALTER TABLE `project_testing`
  ADD PRIMARY KEY (`project_test_id`);

--
-- Indexes for table `project_workgroup`
--
ALTER TABLE `project_workgroup`
  ADD PRIMARY KEY (`project_wgrp_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`property_id`);

--
-- Indexes for table `propertytype`
--
ALTER TABLE `propertytype`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `property_amenity`
--
ALTER TABLE `property_amenity`
  ADD PRIMARY KEY (`prop_ament_id`);

--
-- Indexes for table `property_unit`
--
ALTER TABLE `property_unit`
  ADD PRIMARY KEY (`prop_unit_id`);

--
-- Indexes for table `purchase_invoice`
--
ALTER TABLE `purchase_invoice`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`order_item_id`);

--
-- Indexes for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  ADD PRIMARY KEY (`purchase_pay_id`);

--
-- Indexes for table `push_notify`
--
ALTER TABLE `push_notify`
  ADD PRIMARY KEY (`push_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`quote_id`);

--
-- Indexes for table `raw_materials`
--
ALTER TABLE `raw_materials`
  ADD PRIMARY KEY (`raw_material_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `requisition`
--
ALTER TABLE `requisition`
  ADD PRIMARY KEY (`req_id`);

--
-- Indexes for table `rfq`
--
ALTER TABLE `rfq`
  ADD PRIMARY KEY (`rfq_id`);

--
-- Indexes for table `sale_invoice`
--
ALTER TABLE `sale_invoice`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `sale_order`
--
ALTER TABLE `sale_order`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `sale_order_items`
--
ALTER TABLE `sale_order_items`
  ADD PRIMARY KEY (`sale_item_id`);

--
-- Indexes for table `sale_payments`
--
ALTER TABLE `sale_payments`
  ADD PRIMARY KEY (`sale_pay_id`);

--
-- Indexes for table `scheduling`
--
ALTER TABLE `scheduling`
  ADD PRIMARY KEY (`scheduling_id`);

--
-- Indexes for table `scrap`
--
ALTER TABLE `scrap`
  ADD PRIMARY KEY (`scrap_id`);

--
-- Indexes for table `selection_rule`
--
ALTER TABLE `selection_rule`
  ADD PRIMARY KEY (`rule_id`);

--
-- Indexes for table `selection_rule_segment`
--
ALTER TABLE `selection_rule_segment`
  ADD PRIMARY KEY (`rule_seg_id`);

--
-- Indexes for table `semi_finished`
--
ALTER TABLE `semi_finished`
  ADD PRIMARY KEY (`semi_finished_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`service_id`),
  ADD UNIQUE KEY `service_code` (`code`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `stock_alerts`
--
ALTER TABLE `stock_alerts`
  ADD PRIMARY KEY (`stock_alert_id`);

--
-- Indexes for table `stock_entry`
--
ALTER TABLE `stock_entry`
  ADD PRIMARY KEY (`stock_entry_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `supplier_contacts`
--
ALTER TABLE `supplier_contacts`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `supplier_locations`
--
ALTER TABLE `supplier_locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `supplier_rfq`
--
ALTER TABLE `supplier_rfq`
  ADD PRIMARY KEY (`supp_rfq_id`);

--
-- Indexes for table `supplier_segments`
--
ALTER TABLE `supplier_segments`
  ADD PRIMARY KEY (`segment_id`);

--
-- Indexes for table `supplier_segment_map`
--
ALTER TABLE `supplier_segment_map`
  ADD PRIMARY KEY (`supp_seg_id`);

--
-- Indexes for table `supplier_sources`
--
ALTER TABLE `supplier_sources`
  ADD PRIMARY KEY (`source_id`);

--
-- Indexes for table `supply_list`
--
ALTER TABLE `supply_list`
  ADD PRIMARY KEY (`supply_list_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`tax_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `ticket_comment`
--
ALTER TABLE `ticket_comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet`
--
ALTER TABLE `timesheet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transports`
--
ALTER TABLE `transports`
  ADD PRIMARY KEY (`transport_id`);

--
-- Indexes for table `transport_type`
--
ALTER TABLE `transport_type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`warehouse_id`);

--
-- Indexes for table `workstation`
--
ALTER TABLE `workstation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workstationtype`
--
ALTER TABLE `workstationtype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `work_groups`
--
ALTER TABLE `work_groups`
  ADD PRIMARY KEY (`wgroup_id`);

--
-- Indexes for table `work_group_equip`
--
ALTER TABLE `work_group_equip`
  ADD PRIMARY KEY (`wgroup_equip_id`);

--
-- Indexes for table `work_group_items`
--
ALTER TABLE `work_group_items`
  ADD PRIMARY KEY (`wgroup_item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accountbase`
--
ALTER TABLE `accountbase`
  MODIFY `base_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `account_groups`
--
ALTER TABLE `account_groups`
  MODIFY `acc_group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `additions`
--
ALTER TABLE `additions`
  MODIFY `add_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `amenity`
--
ALTER TABLE `amenity`
  MODIFY `amenity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcementid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `attach_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- AUTO_INCREMENT for table `auto_transaction`
--
ALTER TABLE `auto_transaction`
  MODIFY `autotrans_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `auto_trans_list`
--
ALTER TABLE `auto_trans_list`
  MODIFY `trans_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bankaccounts`
--
ALTER TABLE `bankaccounts`
  MODIFY `bank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bom`
--
ALTER TABLE `bom`
  MODIFY `bom_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `bom_items`
--
ALTER TABLE `bom_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `bom_operation`
--
ALTER TABLE `bom_operation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bom_operation_list`
--
ALTER TABLE `bom_operation_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `bom_scrap_items`
--
ALTER TABLE `bom_scrap_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `calender_events`
--
ALTER TABLE `calender_events`
  MODIFY `event_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `contractors`
--
ALTER TABLE `contractors`
  MODIFY `contractor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `contractor_payments`
--
ALTER TABLE `contractor_payments`
  MODIFY `contractor_pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contracttype`
--
ALTER TABLE `contracttype`
  MODIFY `cont_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `contract_commend`
--
ALTER TABLE `contract_commend`
  MODIFY `discussion_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `contract_renewals`
--
ALTER TABLE `contract_renewals`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `contract_tasks`
--
ALTER TABLE `contract_tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `costing`
--
ALTER TABLE `costing`
  MODIFY `costing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `credits_applied`
--
ALTER TABLE `credits_applied`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `credit_items`
--
ALTER TABLE `credit_items`
  MODIFY `credit_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credit_notes`
--
ALTER TABLE `credit_notes`
  MODIFY `credit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `currency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `cust_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `customer_billingaddr`
--
ALTER TABLE `customer_billingaddr`
  MODIFY `billingaddr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customer_contacts`
--
ALTER TABLE `customer_contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `customer_shippingaddr`
--
ALTER TABLE `customer_shippingaddr`
  MODIFY `shippingaddr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `custom_fields`
--
ALTER TABLE `custom_fields`
  MODIFY `cf_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `custom_field_values`
--
ALTER TABLE `custom_field_values`
  MODIFY `cfv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=264;

--
-- AUTO_INCREMENT for table `deductions`
--
ALTER TABLE `deductions`
  MODIFY `deduct_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `delivery_records`
--
ALTER TABLE `delivery_records`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `designation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dispatch`
--
ALTER TABLE `dispatch`
  MODIFY `dispatch_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `emp_attendance`
--
ALTER TABLE `emp_attendance`
  MODIFY `attend_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `equip_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `erpcontract`
--
ALTER TABLE `erpcontract`
  MODIFY `contract_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `erpexpenses`
--
ALTER TABLE `erpexpenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `erp_companyinfo`
--
ALTER TABLE `erp_companyinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `erp_expenses_categories`
--
ALTER TABLE `erp_expenses_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `erp_goals`
--
ALTER TABLE `erp_goals`
  MODIFY `goals_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `erp_groups`
--
ALTER TABLE `erp_groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `erp_groups_map`
--
ALTER TABLE `erp_groups_map`
  MODIFY `groupmap_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=344;

--
-- AUTO_INCREMENT for table `erp_jobqueue`
--
ALTER TABLE `erp_jobqueue`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `erp_log`
--
ALTER TABLE `erp_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3481;

--
-- AUTO_INCREMENT for table `erp_users`
--
ALTER TABLE `erp_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `estimates`
--
ALTER TABLE `estimates`
  MODIFY `estimate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `estimate_items`
--
ALTER TABLE `estimate_items`
  MODIFY `est_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `expense_items`
--
ALTER TABLE `expense_items`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `expense_task`
--
ALTER TABLE `expense_task`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `finished_goods`
--
ALTER TABLE `finished_goods`
  MODIFY `finished_good_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `inventory_requisition`
--
ALTER TABLE `inventory_requisition`
  MODIFY `invent_req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inventory_warehouse`
--
ALTER TABLE `inventory_warehouse`
  MODIFY `invent_house_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;

--
-- AUTO_INCREMENT for table `knowledgebase_groups`
--
ALTER TABLE `knowledgebase_groups`
  MODIFY `group_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `knowledge_base`
--
ALTER TABLE `knowledge_base`
  MODIFY `article_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `knowledge_base_feedback`
--
ALTER TABLE `knowledge_base_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `lead_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `lead_source`
--
ALTER TABLE `lead_source`
  MODIFY `source_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `mrp_bom`
--
ALTER TABLE `mrp_bom`
  MODIFY `bom_id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mrp_dataset_forecast`
--
ALTER TABLE `mrp_dataset_forecast`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `mrp_scheduling`
--
ALTER TABLE `mrp_scheduling`
  MODIFY `mrp_scheduling_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notify_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `planning`
--
ALTER TABLE `planning`
  MODIFY `planning_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `purchase_invoice`
--
ALTER TABLE `purchase_invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `quote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `requisition`
--
ALTER TABLE `requisition`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rfq`
--
ALTER TABLE `rfq`
  MODIFY `rfq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sale_invoice`
--
ALTER TABLE `sale_invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `sale_order`
--
ALTER TABLE `sale_order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sale_order_items`
--
ALTER TABLE `sale_order_items`
  MODIFY `sale_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `sale_payments`
--
ALTER TABLE `sale_payments`
  MODIFY `sale_pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `scrap`
--
ALTER TABLE `scrap`
  MODIFY `scrap_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `semi_finished`
--
ALTER TABLE `semi_finished`
  MODIFY `semi_finished_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ticket_comment`
--
ALTER TABLE `ticket_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `timesheet`
--
ALTER TABLE `timesheet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `warehouse_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `workstation`
--
ALTER TABLE `workstation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `workstationtype`
--
ALTER TABLE `workstationtype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
