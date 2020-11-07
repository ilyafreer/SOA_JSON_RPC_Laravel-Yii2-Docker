<?php

namespace App\Http\Controllers;

use App\Models\FormOne,
    App\Models\FormTwo,
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
        $randFormId = self::getRandIdForForm();
        $form =
            <<<EOT
<div class="container">
    <div class="row">
        <form name="$randFormId">
            <input type="hidden" name="clientToken" value="{token}">
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
            'method':'saveFormOne',
            'token':form.clientToken.value,
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
    public function saveFormOne(Request $request): array
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

    public function getFormTwo(Request $request): array
    {
        $randFormId = self::getRandIdForForm();
        $form =
            <<<EOT
<div class="container">
    <div class="row">
        <form name="$randFormId">
            <input type="hidden" name="clientToken" value="{token}">
            <div class="form-group">
                <label for="exampleInputName">Укажите ваше имя</label>
                <input type="email" name="name" class="form-control" id="exampleInputName" placeholder="Имя">
            </div>
             <div class="form-group">
                <label for="exampleInputEmail2">Укажите почту для обратной связи</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail2" aria-describedby="emailHelp" placeholder="Enter email">
            </div>
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Напишите ваше сообщение</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" name="text" rows="3"></textarea>
            </div>
            <button type="button" class="btn btn-primary" onclick="func$randFormId()">Отправить</button>
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
            'method':'saveFormTwo',
            'token':form.clientToken.value,
            'params':{
                'email':form.email.value,
                'name':form.name.value,
                'text':form.text.value
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
    public function saveFormTwo(Request $request): array
    {
        $params = json_decode($request->getContent(), true);
        $params = $params['params'];
        $rules = [
            'email' => 'required',
            'name' => 'required',
            'text' => 'required|min:20|max:500'
        ];

        // Валидация параметров
        $validator = Validator::make($params, $rules);
        if ($validator->passes()) {
            $formTwo = new FormTwo();
            $formTwo->email = $params['email'];
            $formTwo->name =  $params['name'];
            $formTwo->text = $params['text'];
            $formTwo->save();
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

    /**
     * Возвращает уникальный номер формы
     * @return string
     */
    private static function getRandIdForForm():string
    {
        return 'id' . substr(md5(rand(99, 9999)), 0, 5);
    }
}
