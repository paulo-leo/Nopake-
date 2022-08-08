<?php
/*
Obs: O JWT tem palavras reservadas e recomendadas para serem colocadas dentro do payload. São elas:

    “iss” O domínio da aplicação geradora do token. 
    OBS: No caso do "iss" ele deverá ser informado com o método appURL($iss)
    “sub” É o assunto do token, mas é muito utilizado para guarda o ID do usuário
    “aud” Define quem pode usar o token
    “exp” Data para expiração do token
    “nbf” Define uma data para qual o token não pode ser aceito antes dela
    “iat” Data de criação do token
    “jti” O id do token

*/

namespace Nopadi\Http;

use Nopadi\Http\Request;

class JWT
{

    private $code; //Código da resposta HTTP
    private $message; //Mensagem da resposta HTTP
    private $key = 'jwt'; //Chave da aplicação que está usando o token
    private $iss = 'localhost'; //O dominio da aplicação geradora do token
    private $charset = 'utf-8'; //Conhunto de caracters para resposta do JWT
    private $payload = array(); //Salva um array com todos os dados do payload

    /*Define o valor da chave do aplicativo que irá usar o token JWT*/
    final public function appKey($value)
    {
        $this->key = $this->key . '_' . $value;
    }

    /*Define o formato de caracters de saída da função responde*/
    final public function appCharset($value)
    {
        $this->charset =  $value;
    }

    /*Define o dominio da aplicativo que irá usar o token JWT. Geralmente é usada o nome da URL onde está hospedado a aplicação*/
    final public function appHost($url)
    {
        $this->iss = $url;
    }

    /*Cria um token e retonar um token JWT*/
    final public function createToken($payload)
    {

        if (isset($payload['iss'])) unset($payload['iss']);
        $payload['iss'] = $this->iss;

        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        $header = json_encode($header);
        $header = base64_encode($header);

        $payload = json_encode($payload);
        $payload = base64_encode($payload);

        $signature = hash_hmac('sha256', "$header.$payload", $this->key, true);
        $signature = base64_encode($signature);

        return "$header.$payload.$signature";
    }
	
	/*Atualiza o payload do token*/
	final public function updatePayload($token,$callback=null)
	{

		$token = explode('.',$token);

		$header = $token[0];
		$payload = isset($token[1]) ? $token[1] : false ;
		$signature = isset($token[2]) ? $token[2] : false ;
	
		
		if($payload && $signature)
		{
		  $payload = base64_decode($payload);
		  $payload = (array) json_decode($payload);

		  if(is_callable($callback))
	      {
			$callback = call_user_func($callback, $payload); 	
			$payload = is_array($callback) ? $callback : $payload;
	      }

		  if(is_array($callback))
	      {
			  $payload = array_merge($callback,$payload); 	
	      }

		  $payload = json_encode($payload);
          $payload = base64_encode($payload);
		
		   $signature = hash_hmac('sha256', "$header.$payload", $this->key, true);
           $signature = base64_encode($signature);

	     }
		 return "$header.$payload.$signature";

	}

    /*gera um login retornando os dados do usuário autenticado junto com o token gerado*/
    final public function login($payload,$merge=false/*Retorna os dados do payload na response*/){

        $token = $this->createToken($payload);
         
        header("HTTP/1.0 201 Created");
        
        $this->code = 201;

        $message = array(
            'code'=>201,
            'token'=>$token,
            'message'=>'JWT_successfully_generated',
			'created_in'=>NP_DATETIME
        );
         
		if($merge){
			
			if(array_key_exists('code',$payload)) unset($payload['code']); 
			if(array_key_exists('message',$payload)) unset($payload['message']); 
			if(array_key_exists('created_in',$payload)) unset($payload['created_in']); 
			
			return $this->response(array_merge($message,$payload));
			
		}else return $this->response($message);
    }

    /*verifica se o token JWT é válido, se for válido, irá retonar o token sem o prefix JWT, caso contário, retonará falso*/
    final public function checkToken($token)
    {
            $part = explode(".", $token);
            $header = isset($part[0]) ? $part[0] : null;
            $payload = isset($part[1]) ? $part[1] : null;
            $signature = isset($part[2]) ? $part[2] : null;

            $valid = hash_hmac('sha256', "$header.$payload", $this->key, true);
            $valid = base64_encode($valid);

            if ($signature == $valid) {

                 $this->payload = base64_decode($payload);
                 $this->payload = json_decode($this->payload,true);
                 return $token;
                
            } else {
                
                return false;

            }
      
    }

    /*Retorna um array com todos os dados do payload*/
    final public function all()
    {
         return $this->payload;
        
    }

    /*Retorna o valor de um índice do payload caso ele exista. Se for passado um segundo parametro, ele só considerará a existencia do valor se o segundo parametro for igual ao valor armazenado caso o índice exista. Caso nenhuma consição seja atendida, a função retornará null*/
    final public function get($key,$value=null)
    {
       
        if(is_null($value)){
            $key = array_key_exists($key,$this->payload) ? $this->payload[$key] : null;
        }else{
            $key = array_key_exists($key,$this->payload) ? $key : null;
            $key = (!is_null($key) && in_array($value,$this->payload)) ? $this->payload[$key] : null;
        }

        return $key;

    }

    /*Modifica código do método response*/
	final public function setCode($code)
	{
		$this->code = $code;
		header("Status: {$this->code} ok");
	}
	
	/*Modifica mensagem do método response*/
	final public function setMessage($message)
	{
		$this->message = $message;
	}

    /*Imprime a resposta da requisição com código e a mensagem no formato JSON*/
    final public function response($array2 = null,$use_code=false)
    {
		$array2 = is_object($array2) ? (array) $array2 : $array2;
		
	    $header_code = isset($array2['code']) ? true : false;
        //Se o código for null será igual a 406
        $this->code = is_null($this->code) ? 406 : $this->code;

        $array = array('code' => $this->code, 'message' => $this->message);

        if (is_string($array2)) $array = array_merge($array, ['message' => $array2]);
        if (is_array($array2)) $array = array_merge($array, $array2);

        header('Content-Type: application/json;charset=' . $this->charset);
		
		if($header_code){ header("HTTP/1.1 {$array['code']} {$array['message']}"); }
		
		if($use_code)
		{
			header("HTTP/1.1 {$this->code} {$this->message}");
		}
		
        return json_encode($array);
    }
   
    /*Authorization: 'Bearer ' + varToken
	Pega o header com o name "authorization" caso o argumento passado seja "null"*/
    final public function auth($token=null)
    {
        $request = new Request;
        
        if(is_null($token))
		{
	        $token = $request->getHeader('Authorization');
		}


        if ($token) {

            $token =  $this->checkToken($token);

            if ($token) {

                /*Token válido e autorizado*/
                header("HTTP/1.0 200 Authorized");
                $this->code = 200;
                $this->message = 'authorized';
                return true;
            } else {

                /*O tokenn foi enviado, mas é inválido*/
                header("HTTP/1.0 403 Forbidden");
                $this->code = 403;
                $this->message = 'Invalid_JWT';
                return false;
            }
        } else {

            /*Token JWT não encontrado no cabeçalho da autorização*/
            header("HTTP/1.0 401 Unauthorized");
            $this->code = 401;
            $this->message = 'authorization_not_found';
            return false;
        }
    }
}
