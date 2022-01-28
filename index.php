<?php

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Credit calculate</title>
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div id="app">
    <div class="row" v-if="currencies.length > 0">
        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col p-3 justify-center align-items-center justify-content-center d-flex my-auto">
                    <div class="row">
                        <div class="col">
                            <label for="customRange1" class="form-label">Сумма кредита {{ creditAmount }}</label>
                            <input type="range" min="500" max="25000" v-model="creditAmount" step="100" class="form-range" id="customRange1">
                            <label for="customRange1" class="form-label">Срок кредита {{ creditReturnMonth }}</label>
                            <input type="range" class="form-range" min="3" max="84" v-model="creditReturnMonth" id="customRange1">
                        </div>
                        <div class="col" >
                            <select class="form-select" @change="changeCurrency($event)">
                                <option v-for="(currency, index) in currencies"
                                        :key="index"
                                        :selected= "currency.ID === 'EUR'"
                                        :value="JSON.stringify(currency)"
                                >{{currency.ID}}</option>
                            </select>
                            <div class="input-group mb-3 mt-5">
                                <span class="input-group-text" id="basic-addon1">Ежемесячный платёж</span>
                                <input type="text" class="form-control" disabled v-model="returnMonthPay()"  aria-describedby="basic-addon1">
                            </div>
                            <div class="input-group mb-3 mt-5">
                                <button type="button" class="btn btn-primary" @click="showCreditForm = !showCreditForm">Оформить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-if="showCreditForm">
                <div class="col p-3 justify-center align-items-center justify-content-center d-flex my-auto">
                    <form method="POST" action="submit.php">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Имя</label>
                            <input type="text" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Имя">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Email</label>
                            <input type="email" class="form-control" name="email" id="exampleInputPassword1" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Number</label>
                            <input type="number" name="number" class="form-control" id="exampleInputPassword1" placeholder="+371">
                        </div>
                        <input type="hidden" name="monthPay" v-model="returnMonthPay()">
                        <input type="hidden" name="currency" v-model="selectedCurrency">
                        <input type="hidden" name="creditAmount" v-model="creditAmount">
                        <button type="submit" class="btn btn-primary mt-3">Отправлять</button>
                    </form>
               </div>
            </div>
        </div>
    </div>
</div>

<script>
    var app = new Vue({
        el: '#app',
        data: {
            currencies: [],
            creditAmount: 500,
            selectedCurrency: 'EUR',
            selectedCurrencyRate: 1,
            creditReturnMonth: 10,
            creditPercent: 7,
            showCreditForm: false
        },
        mounted() {
            this.getCurrency();
        },
        methods: {
            getCurrency: async function () {
                const responce = await fetch('currency.php');
                const data = await responce.text();
                this.currencies = [...JSON.parse(data)[1].Currency];
                this.currencies.push({
                    ID: 'EUR',
                    Rate: 1
                })
                console.log(this.currencies);
                console.log(this.currencies.length);
            },
            changeCurrency: function (event) {
                const data = JSON.parse(event.target.value);
                this.selectedCurrency = data.ID;
                this.selectedCurrencyRate = data.Rate;
            },
            returnMonthPay: function () {
                const priceWithoutPercent = (this.creditAmount * this.selectedCurrencyRate);
                console.log(priceWithoutPercent);
                const percent = ((this.creditAmount * this.selectedCurrencyRate) * this.creditPercent) / 100;
                console.log(percent);
                return Math.floor((priceWithoutPercent + percent) / this.creditReturnMonth);
            }
        }
    })
</script>
</body>
</html>
