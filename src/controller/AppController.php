<?php

namespace App\controller;

use App\assets\lib\Helpers;
use App\http\Request;
use App\http\Response;
use App\model\AjaxResolver;
use Exception;

/**
 * Class AppController
 * @see Controller
 */
final class AppController extends Controller
{
    /**
     * @param Response $res
     * @return void
     */
    public static function index(Response $res)
    {
        $res->send(array(), 'pages/Listagem');
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public static function indexAfterPost(Request $request, Response $response)
    {
        $result = $response::jsonToArray(self::apiIndex($request));
        if (isset($result['error'])) {
            self::redirect('/');
        } else {
            self::view('pages/Listagem', $result);
        }
    }

    /**
     * @param Request $req
     * @return string
     */
    public static function apiIndex(Request $req)
    {
        $body = $req->getParsedBody();
        $nomeOuCpf = isset($body['nomeOuCpf']) ? $body['nomeOuCpf'] : null;
        if (
            isset($body['start'], $body['end'], $body['averbador'], $body['condominioValue']) &&
            Helpers::stringIsOk($body['start']) &&
            Helpers::stringIsOk($body['end']) &&
            Helpers::stringIsOk($body['averbador']) &&
            Helpers::stringIsOk($body['condominioValue'])
        ) {
            return Helpers::toJson(
                AjaxResolver::boletos(
                    $body['start'],
                    $body['end'],
                    $body['averbador'],
                    $nomeOuCpf,
                    $body['condominioValue']
                )
            );
        }
        return Helpers::toJson(array('error' => true, 'message' => 'Something is missin on Request Body', 'raw' => $body));
    }

    /**
     * @param Request $request
     * @return array|bool|string
     */
    public static function listCondominiosBy(Request $request)
    {
        $body = $request->getParsedBody();
        //exit(var_dump($body['cpfcnpj'], $body['campo1'], $body['campo2']));
        if (isset($body['cpfcnpj'], $body['campo1'], $body['campo2'])) {
            $result = AjaxResolver::condominios($body['cpfcnpj'], $body['campo1'], $body['campo2']);
            return $result === false ?
                Request::toJson(array('error' => true, 'message' => 'something is worng inside AjaxResolver::condominios', 'raw' => array($result, $body))) :
                $result;
        }
        return Request::toJson(array('error' => true, 'message' => 'miss something in body request', 'raw' => $body));
    }


    /**
     * allAboutTheRequest
     * @Description All about content revice from request user
     * @param Request $req
     * @return string
     * @throws Exception
     */
    public static function allAboutTheRequest(Request $req)
    {
        return $req::toJson(
            array(
                'body' => $req->getParsedBodyContent(),
                'params' => $req->getQueryParams(),
                'parsedBody' => $req->getParsedBody(),
            )
        );
    }

    /**
     * @param Request $request
     */
    public static function relatorio(Request $request)
    {
        $body = $request->getQueryParams();
        $nomeOuCpf = isset($body['nomeOuCpf']) && !empty($body['nomeOuCpf']) ? $body['nomeOuCpf'] : null;
        if (
            isset($body['start'], $body['end'], $body['averbador'], $body['condominioValue']) &&
            Helpers::stringIsOk($body['start']) &&
            Helpers::stringIsOk($body['end']) &&
            Helpers::stringIsOk($body['averbador']) &&
            Helpers::stringIsOk($body['condominioValue'])
        ) {
            AjaxResolver::relatorio($body['start'],
                $body['end'],
                $body['averbador'],
                $nomeOuCpf,
                $body['condominioValue']);
        } else {
            echo Request::toJson(array('error' => true, 'message' => 'miss something in body request', 'raw' => $body));
        }
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws Exception
     */
    public static function atualizarContratos(Request $request, Response $response)
    {
        $body = $request->getParsedBodyContent();
        if (isset(
            $body['start'],
            $body['end'],
            $body['vencimento'],
            $body['averbador'],
            $body['nomeOuCpf'],
            $body['condominioValue']
        )) {
            $result = AjaxResolver::attContratos(
                $body['start'],
                $body['end'],
                $body['vencimento'],
                $body['averbador'],
                $body['nomeOuCpf'],
                $body['condominioValue']
            );
            if (isset($result['error']) && $result['error'] == true) {
                $response->withStatus(400);
            }
            return Request::toJson($result);
        }
        $response->withStatus(400);
        return Request::toJson(array('error' => true, 'message' => 'miss something in body request', 'raw' => $body));

    }
}
