<?php
// This code example is based on code from the Echove project.
// For the entire PHP SDK, please visit http://echove.net/

// Turn on error reporting during development
ini_set('error_reporting', E_ALL);
ini_set('display_errors', TRUE);
$result=0;
// Instantiate the Brightcove class
$bc = new Brightcove(
	'5lr_GNp0hRM6wYM1r_lfuSZBSAGKVt94K2Kgb4sBLHNSKxdZg6qLOA..',
	'6FHUqplUwtI-VGmNoT9_fKuqoKqNHzFmazmVce4w8SZPSiLdFLjkMQ..'
);

// Set the data for the new video DTO
$metaData = array(
	'name' => $_POST['bcName'],
	'shortDescription' => $_POST['bcShortDescription'],
);

// Rename the file to its original file name (instead of temp names like "a445ertd3")
$file = $_FILES['bcVideo'];
rename($file['tmp_name'], '/tmp/' . $file['name']);
$file = '/tmp/' . $file['name'];

// Send the file to Brightcove
echo $bc->createVideo($file, $metaData);

class Brightcove {
        public $token_read = '';
        public $token_write = '';
        public $read_url = 'http://api.brightcove.com/services/library?';
        public $write_url = 'http://api.brightcove.com/services/post';

	public function __construct($token_read, $token_write = NULL ) {
		$this->token_read = $token_read;
		$this->token_write = $token_write;
	}

	public function createVideo($file = NULL, $meta) {
		$request = array();
		$post = array();
		$params = array();
		$video = array();

		foreach($meta as $key => $value) {
			$video[$key] = $value;
		}
		$params['token'] = $this->token_write;
		$params['video'] = $video;

		$post['method'] = 'create_video';
		$post['params'] = $params;

		$request['json'] = json_encode($post);

		if($file) {
			$request['file'] = '@' . $file;
		}
		
		print_r($request);
		

		// Utilize CURL library to handle HTTP request
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->write_url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_VERBOSE, TRUE );
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 36000);
		curl_setopt($curl, CURLOPT_TIMEOUT, 36000);
		$response = curl_exec($curl);

		curl_close($curl);

		// Responses are transfered in JSON, decode into PHP object
		$json = json_decode($response);
		$respid= $json->result;
		echo '<script language="javascript" type="text/javascript"> var res='.$respid.';</script>';

		// Check request error code and re-call createVideo if request
                // returned a 213 error. A 213 error occurs when you have 
                // exceeded your allowed number of concurrent write requests
		if(isset($json->error))	{
			if($json->error->code == 213) {
				return $this->createVideo($file, $meta);
			} else {
				return FALSE;
			}
		} else {
			return $response;
		}
	}
}

?>
<script language="javascript" type="text/javascript">window.top.window.stopUpload(1);</script>  
<script language="javascript" type="text/javascript">window.top.window.respUpload(res);</script>    