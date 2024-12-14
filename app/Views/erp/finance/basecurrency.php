<div class="alldiv flex widget_title">
    <h3>Exchange Rate</h3>
    <div class="title_right">
        <a href="<?php echo url_to('erp.finance.currency'); ?>" class="btn bg-success"><i class="fa fa-reply"></i> Back </a>
    </div>
</div>

<div class="alldiv">
    <form action="#" method="post" class="flex" id="basecurrency_add_form">
        <div class="form-width-2">
            <div class="currency">
                <label class="form-label" style="font-weight: 600;   margin: 8px 0px;  display: inline-block;   color: rgba(0, 0, 0, 0.7);">From Currency</label>
                <select id="currency-one" style="width: 100%; height:40px; color:#5e5669de;  border: 1px solid #4443453b; border-radius: 4px; font-size: 14px;   padding: 4px 12px; margin-bottom: 12px;">
                    <option value="AED">AED</option>
                    <option value="ARS">ARS</option>
                    <option value="AUD">AUD</option>
                    <option value="BGN">BGN</option>
                    <option value="BRL">BRL</option>
                    <option value="BSD">BSD</option>
                    <option value="CAD">CAD</option>
                    <option value="CHF">CHF</option>
                    <option value="CLP">CLP</option>
                    <option value="CNY">CNY</option>
                    <option value="COP">COP</option>
                    <option value="CZK">CZK</option>
                    <option value="DKK">DKK</option>
                    <option value="DOP">DOP</option>
                    <option value="EGP">EGP</option>
                    <option value="EUR">EUR</option>
                    <option value="FJD">FJD</option>
                    <option value="GBP">GBP</option>
                    <option value="GTQ">GTQ</option>
                    <option value="HKD">HKD</option>
                    <option value="HRK">HRK</option>
                    <option value="HUF">HUF</option>
                    <option value="IDR">IDR</option>
                    <option value="ILS">ILS</option>
                    <option value="INR">INR</option>
                    <option value="ISK">ISK</option>
                    <option value="JPY">JPY</option>
                    <option value="KRW">KRW</option>
                    <option value="KZT">KZT</option>
                    <option value="MXN">MXN</option>
                    <option value="MYR">MYR</option>
                    <option value="NOK">NOK</option>
                    <option value="NZD">NZD</option>
                    <option value="PAB">PAB</option>
                    <option value="PEN">PEN</option>
                    <option value="PHP">PHP</option>
                    <option value="PKR">PKR</option>
                    <option value="PLN">PLN</option>
                    <option value="PYG">PYG</option>
                    <option value="RON">RON</option>
                    <option value="RUB">RUB</option>
                    <option value="SAR">SAR</option>
                    <option value="SEK">SEK</option>
                    <option value="SGD">SGD</option>
                    <option value="THB">THB</option>
                    <option value="TRY">TRY</option>
                    <option value="TWD">TWD</option>
                    <option value="UAH">UAH</option>
                    <option value="USD" selected>USD</option>
                    <option value="UYU">UYU</option>
                    <option value="VND">VND</option>
                    <option value="ZAR">ZAR</option>
                </select>

            </div>
        </div>

        <div class="form-width-2">
            <div class="form-group">
                <label class="form-label">Value</label>
                <input class="form_control dt-search" type="number" id="amount-one" placeholder="0" value="1" />
                <p class="error-text"></p>
            </div>
        </div>

        <div class="form-width-1 text-center">
            <div class="swap-rate-container mt-3">
                <div class="btn bg-primary" id="swap">
                    Swap
                </div>
                <br>
                <div class="rate mt-3">
                    <h2 id="rate"></h2>
                </div>
                <br>
            </div>
        </div>


        <div class="form-width-2">
            <div class="currency">
                <label class="form-label" style="font-weight: 600;   margin: 8px 0px;  display: inline-block;   color: rgba(0, 0, 0, 0.7);">To Currency</label>
                <select id="currency-two" style="width: 100%; height:40px; color:#5e5669de;  border: 1px solid #4443453b; border-radius: 4px; font-size: 14px;   padding: 4px 12px; margin-bottom: 12px;">
                    <option value="AED">AED</option>
                    <option value="ARS">ARS</option>
                    <option value="AUD">AUD</option>
                    <option value="BGN">BGN</option>
                    <option value="BRL">BRL</option>
                    <option value="BSD">BSD</option>
                    <option value="CAD">CAD</option>
                    <option value="CHF">CHF</option>
                    <option value="CLP">CLP</option>
                    <option value="CNY">CNY</option>
                    <option value="COP">COP</option>
                    <option value="CZK">CZK</option>
                    <option value="DKK">DKK</option>
                    <option value="DOP">DOP</option>
                    <option value="EGP">EGP</option>
                    <option value="EUR">EUR</option>
                    <option value="FJD">FJD</option>
                    <option value="GBP">GBP</option>
                    <option value="GTQ">GTQ</option>
                    <option value="HKD">HKD</option>
                    <option value="HRK">HRK</option>
                    <option value="HUF">HUF</option>
                    <option value="IDR">IDR</option>
                    <option value="ILS">ILS</option>
                    <option value="INR" selected>INR</option>
                    <option value="ISK">ISK</option>
                    <option value="JPY">JPY</option>
                    <option value="KRW">KRW</option>
                    <option value="KZT">KZT</option>
                    <option value="MXN">MXN</option>
                    <option value="MYR">MYR</option>
                    <option value="NOK">NOK</option>
                    <option value="NZD">NZD</option>
                    <option value="PAB">PAB</option>
                    <option value="PEN">PEN</option>
                    <option value="PHP">PHP</option>
                    <option value="PKR">PKR</option>
                    <option value="PLN">PLN</option>
                    <option value="PYG">PYG</option>
                    <option value="RON">RON</option>
                    <option value="RUB">RUB</option>
                    <option value="SAR">SAR</option>
                    <option value="SEK">SEK</option>
                    <option value="SGD">SGD</option>
                    <option value="THB">THB</option>
                    <option value="TRY">TRY</option>
                    <option value="TWD">TWD</option>
                    <option value="UAH">UAH</option>
                    <option value="USD">USD</option>
                    <option value="UYU">UYU</option>
                    <option value="VND">VND</option>
                    <option value="ZAR">ZAR</option>
                </select>
            </div>
        </div>

        <div class="form-width-2">
            <div class="form-group">
                <label class="form-label">Value</label>
                <input class="form_control dt-search" type="number" id="amount-two" placeholder="0" value="1" />
                <p class="error-text"></p>
            </div>
        </div>

    </form>
</div>





<!--SCRIPT WORKS -->
</div>
</main>

<script src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/script.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/js/erp.js'; ?>"></script>
<script type="text/javascript">
    let closer = new WindowCloser();
    closer.init();


    // Selectors currency converter
    const currencyOne = document.getElementById("currency-one");
    const currencyTwo = document.getElementById("currency-two");
    const amountOne = document.getElementById("amount-one");
    const amountTwo = document.getElementById("amount-two");
    const rateEl = document.getElementById("rate");
    const swap = document.getElementById("swap");

    // Fetch exchange rate and update DOM
    function calculate() {
        const currency_one = currencyOne.value;
        const currency_two = currencyTwo.value;

        fetch(
                `https://v6.exchangerate-api.com/v6/db9dd06ef07d2112693c914d/latest/${currency_one}`
            )
            .then((res) => res.json())
            .then((data) => {
                const rate = data.conversion_rates[currency_two];

                rateEl.innerText = `1 ${currency_one} = ${rate} ${currency_two}`;

                amountTwo.value = (amountOne.value * rate).toFixed(2);
            });
    }

    // Event listener
    currencyOne.addEventListener("change", calculate);
    amountOne.addEventListener("input", calculate);
    currencyTwo.addEventListener("change", calculate);
    amountTwo.addEventListener("input", calculate);

    swap.addEventListener("click", () => {
        const temp = currencyOne.value;
        currencyOne.value = currencyTwo.value;
        currencyTwo.value = temp;

        calculate();
    });

    calculate();

    /////////////////

    <?php
    if (session()->getFlashdata("op_success")) { ?>
        let alerts = new ModalAlert();
        alerts.invoke_alert("<?php echo session()->getFlashdata('op_success'); ?>", "success");
    <?php
    } else if (session()->getFlashdata("op_error")) { ?>
        let alert = new ModalAlert();
        alert.invoke_alert("<?php echo session()->getFlashdata('op_error'); ?>", "error");
    <?php
    }
    ?>
</script>

</body>

</html>