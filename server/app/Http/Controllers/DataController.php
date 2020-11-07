<?php

namespace App\Http\Controllers;

use App\Models\FormOne,
    Illuminate\Http\Request,
    Illuminate\Support\Facades\Validator;
use App\Services\JsonRpcServer;

class DataController extends Controller
{
    /**
     * Возвращает готовую форму для вставки на клиенте
     * @param array $params
     * @return string
     */
    public function getCasinoBanner(Request $request): array
    {
        $randFormId = 'id' . substr(md5(rand(99, 999)), 0, 5);
        $form =
            <<<EOT
<div class="container">
    <div class="row">
        <form name="$randFormId">
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>
            <button type="button" class="btn btn-primary" onclick="func$randFormId()">Submit</button>
        </form>
    </div>
    <div class="row" id="message$randFormId">

</div>
</div>
<script>
    async function func$randFormId() {
        let form = document.forms.$randFormId;
        let message = document.querySelector('#message$randFormId');
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
        let answer = await response.json();
        if(answer.result.status == 'success'){
            form.innerHTML = answer.result.data
            message.innerHTML = '';
        }else{
            message.innerHTML = answer.result.data;
        }
    }
</script>
EOT;
        return [
            'status' => JsonRpcServer::ANSWER_STATUSES['OK'],
            'data' => $form
        ];
    }

    /**
     * Сохраняет полученные данные в базу
     * @param array $request
     * @return string
     */
    public function sendFormOne(Request $request): array
    {
        $params = json_decode($request->getContent(), true);
        $params = $params['params'];
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        // Валидация параметров
        $validator = Validator::make($params, $rules);
        if ($validator->passes()) {
            $formOne = new FormOne();
            $formOne->email = $params['email'];
            $formOne->password = md5($params['password']);
            $formOne->save();
            return [
                'status' => JsonRpcServer::ANSWER_STATUSES['OK'],
                'data' => 'Данные успешно отправлены'
            ];
        }
        return [
            'status' => JsonRpcServer::ANSWER_STATUSES['FAIL'],
            'data' => 'Проверьте данные в форме'
        ];
    }
}
