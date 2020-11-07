<?php

namespace App\Http\Controllers;


class DataController extends Controller
{
    /**
     * Возвращает готовую форму для вставки на клиенте
     * @param array $params
     * @return string
     */
    public function getCasinoBanner(array $params):string
    {
        $form =
        <<<EOT
<div class="container">
    <div class="row">
        <form name="iuf3">
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>
            <button type="button" class="btn btn-primary" onclick="send()">Submit</button>
        </form>
    </div>
    <div id="response"></div>
</div>
<script>
    async function send() {
        let form = document.forms.iuf3;
        let params = {
            'method':'sendFormOne',
            'params':{
                'email':form.email.value,
                'password':form.password.value
            }
        }
        let requestData = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(params)
        };
        let response = await fetch('/api/send', requestData);
        // let result = await response.json();
        console.log(response);
    }
</script>
EOT;
        return $form;
    }

    public function sendFormOne():string
    {
        return 'Form one saved';
    }
}
