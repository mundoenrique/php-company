<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * An example class
 *
 * The class is empty for the sake of this example.
 *
 * @package    Users
 * @subpackage Controller
 * @author     Rojas Wilmer <rojaswilmer@gmail.com>
 */
class Tests extends CI_Controller {


/**
 * [testMail description]
 * @param  [type] $urlCountry [description]
 * @return [type]             [description]
 */
	public function testMail($urlCountry)
	{
	$this->load->library('email');
	$this->email->from('noreply@novopayment.com', 'EOL SYSTEM');
	$this->email->to('wrojas@novopayment.com'); 
	$this->email->subject('Email Test');
	$this->email->message('Testing the email class.');	
	$this->email->send();
	if ( $this->email->send())
	{
	  echo $this->email->print_debugger();
	}
	}

/**
 * [test description]
 * @return [type] [description]
 */
	public function test($urlCountry){

		np_hoplite_countryCheck($urlCountry); 
        $this->lang->load('lotes');
        $this->lang->load('dashboard');
        $this->lang->load('users');
        $this->lang->load('erroreseol');
        

        $logged_in = $this->session->userdata('logged_in');

        $menuP =$this->session->userdata('menuArrayPorProducto');
		$moduloAct = np_hoplite_existeLink($menuP,"TEBGUR");

        $paisS = $this->session->userdata('pais');


            $FooterCustomInsertJS=["jquery-1.10.2.min.js","jquery-ui-1.10.3.custom.min.js","jquery.balloon.min.js","jquery.dataTables.min.js","header.js","dashboard/widget-empresa.js","jquery.fileupload.js","jquery.iframe-transport.js","jquery-md5.js","lotes/test.js"];
            $FooterCustomJS="";
            $titlePage="Reproceso de Datos";

    //INSTANCIA MENU HEADER 
            $menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
    //INSTANCIA MENU FOOTER 
            $menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);

            $header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>TRUE,'menuHeaderMainActive'=>TRUE,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
            $footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>TRUE,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);
            $content = $this->parser->parse('lotes/test',array(
                "titulo" => $titlePage                          
                ),TRUE);
            $sidebarLotes= $this->parser->parse('dashboard/widget-empresa',array('sidebarActive'=>TRUE),TRUE);

            $datos = array(
                'header'       =>$header,
                'content'      =>$content,
                'footer'       =>$footer,
                'sidebar'      =>$sidebarLotes
                );

            $this->parser->parse('layouts/layout-b', $datos);
      
	}

/**
 * [testUpload description]
 * @return [type] [description]
 */
public function testUpload()
	{
		$config['upload_path'] = APPPATH . 'uploads/';
		$config['allowed_types'] = 'txt|xls';
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());
			print_r($error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			print_r($data);
		}	
	}

/**
 * [testSSH description]
 * @return [type] [description]
 */
public function testSSH(){
	/*
	$connection = ssh2_connect("180.180.159.22", 22);

if(ssh2_auth_password($connection, "tomcat", "tomcat"))
  printf("CONNECTED");
else
  printf("ERROR");
*/
  $ch = curl_init();
$localfile = '2000000001051300000.txt';
$fp = fopen($localfile, 'r');
//echo getcwd();
curl_setopt($ch, CURLOPT_URL, 'sftp://180.180.159.22:22/opt/tomcat/TempLotes/'.$localfile);
curl_setopt($ch, CURLOPT_USERPWD, "tomcat:tomcat");
curl_setopt($ch, CURLOPT_UPLOAD, 1);
curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);
curl_setopt($ch, CURLOPT_INFILE, $fp);
curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localfile));
curl_exec ($ch);
$error_no = curl_errno($ch);
curl_close ($ch);
if ($error_no == 0) {
$error = 'File uploaded succesfully.';
} else {
$error = 'File upload error.';
}
print_r($error_no);
}

/**
 * [testGettext description]
 * @return [type] [description]
 */
function testGettext(){
if (!function_exists("gettext"))
{
    echo "gettext is not installed\n";
}
else
{
    echo "gettext is supported\n";
}
// Set language to German 
putenv ("LANG=de"); 
// set the locale into the instance of gettext 
setlocale(LC_ALL, ""); 
// Specify location of translation tables 
bindtextdomain ("myPHPApp", "./locale"); 
// Choose domain 
textdomain ("myPHPApp"); 
print (gettext ("Welcome to My PHP Application")); 
}

/**
 * [testZip description]
 * @return [type] [description]
 */
public function testZip(){

$filename = tempnam('/opt/httpd-2.4.4/vhost/online/application/uploads', 'zlibtest') . '.gz';
echo "<html>\n<head></head>\n<body>\n<pre>\n";
$s = "Sólo una prueba, prueba, prueba, prueba, prueba, prueba!\n";
// abre el archivo para escribir con compresión máxima 
$zp = gzopen($filename, "w9");
// escribe la cadena en el archivo
gzwrite($zp, $s);
// cierra el archivo
gzclose($zp);
// abre el archivo para lectura
$zp = gzopen($filename, "r");
// lee el tercer carácter
echo gzread($zp, 3);
// salida hasta el fin del archivo y lo cierra
gzpassthru($zp);
gzclose($zp);
echo "\n";
// abre el archivo y muestra el contenido (por segunda vez).
if (readgzfile($filename) != strlen($s)) {
        echo "Error con funciones de zlib!";
}
unlink($filename);
echo "</pre>\n</body>\n</html>\n";
}

/**
 * [testFtp description]
 * @return [type] [description]
 */
public function testFtp(){
$ftp_server = "180.180.159.11";
// establecer una conexión o finalizarla
$conn_id = ftp_connect($ftp_server,21,"10") or die("No se pudo conectar a $ftp_server"); 
$login_result = ftp_login($conn_id, "root", "#D3s4Web");
// imprimir el directorio actual
// cambiar al directorio public_html
ftp_chdir($conn_id, 'opt/httpd-2.4.4/vhost');
echo ftp_pwd($conn_id); // /public_html
// cerrar la conexión ftp
ftp_close($conn_id);
}

/**
 * [testExif description]
 * @return [type] [description]
 */
public function testExif(){
	$image = "/opt/httpd-2.4.4/vhost/online/application/uploads/test.png";
	$image2 = "/opt/httpd-2.4.4/vhost/online/application/uploads/test2.jpeg";
$types = array(
1 => "GIF",
2 => "JPEG",
3 => "PNG",
4 => "SWF",
5 => "PSD",
6 => "BMP",
7 => "TIFF",
8 => "TIFF"
);
$imagetype = exif_imagetype($image);
if (array_key_exists($imagetype, $types)) {
echo "Image type is: " . $types[$imagetype];
} else {
echo "Not a valid image type";
}

if (file_exists($image2)) {
    echo '
';
        $exif = exif_read_data($image2, 0, true);
        foreach ($exif as $key => $section) {
            foreach ($section as $name => $val) {
                echo "$key.$name: $val";
            }
        }
    echo '
';
}

}

/**
 * [testFile description]
 * @return [type] [description]
 */
public	function testFile($urlCountry)
	{
	
	/*// print_r($this->session->all_userdata());
	// var_dump($_SERVER);

	 	$idProducto = $this->session->userdata('idProductoS');
		$cid = $this->session->userdata('acrifS');
		$accodcia = $this->session->userdata('accodciaS');
		$codgrupoe = $this->session->userdata("accodgrupoeS");
		
	    $responseMenuPorProducto =$this->callWSMenuPorProducto('P',$cid,$accodcia,$codgrupoe, 'Pe');
	    print_r($responseMenuPorProducto);

		//$bytes =[37,80,68,70,45,49,46,52,10,37,-30,-29,-49,-45,10,51,32,48,32,111,98,106,32,60,60,47,76,101,110,103,116,104,32,49,57,52,50,47,70,105,108,116,101,114,47,70,108,97,116,101,68,101,99,111,100,101,62,62,115,116,114,101,97,109,10,120,-100,-99,89,95,111,-37,54,16,127,-9,-89,-32,-53,-128,22,91,20,-110,34,-11,39,111,-86,-93,20,30,82,-37,-75,-100,2,-61,60,20,-102,-83,-90,30,36,-53,-109,-99,109,-40,55,-36,-13,62,-54,-34,-10,52,-110,18,-59,-65,78,-77,22,9,66,-35,-3,126,-57,-69,35,-17,72,-87,-65,78,-34,-84,39,97,4,-30,20,-125,-11,110,2,-63,21,77,-121,1,38,-61,32,-114,-122,65,-104,12,-125,72,14,-88,28,-112,1,3,-7,-97,-21,59,12,-40,-13,-89,-55,-85,-69,125,93,-127,31,-89,55,-101,77,-42,109,63,-17,127,107,79,96,87,-127,99,-41,62,118,101,83,50,-23,-79,-36,126,-82,64,-47,126,58,-1,94,118,21,-72,107,-97,14,-69,-14,-68,111,15,-93,110,-35,54,-37,-14,12,-30,0,6,56,-34,108,-114,37,99,86,-25,-82,61,109,54,85,91,111,54,-53,-22,99,123,-6,120,-82,-2,56,87,-121,-35,-57,101,112,-2,-29,-4,-45,-4,58,123,-67,-2,69,-70,-109,-81,39,-17,39,-65,-118,95,12,-66,103,-46,-73,19,24,80,-16,-69,-120,59,-127,-96,-103,-96,24,-119,81,61,41,6,21,33,76,-62,-94,107,-6,-111,-48,9,-108,-112,41,-68,28,-23,56,-37,6,10,41,-64,24,116,-43,-88,17,-84,112,-76,-60,48,-75,112,70,-120,-8,-64,-108,72,-72,101,58,28,-35,19,112,-97,81,37,-109,12,-41,-122,112,15,-10,-18,-67,-25,-37,1,1,-98,56,4,-104,46,78,-40,-74,104,-40,-126,34,-112,-120,5,-27,105,101,-7,-76,80,-15,-128,-6,-54,101,-25,70,117,115,81,-60,-51,-67,-70,-72,25,28,60,20,-8,-117,91,-92,110,31,91,-74,51,-114,-121,71,-71,51,-52,16,48,14,-125,16,75,59,60,88,-44,-57,-79,88,-35,-26,115,112,-101,-125,34,95,125,-104,77,103,11,-105,75,34,54,41,91,-88,-120,-102,-119,122,91,117,-43,97,-69,47,121,-32,-117,99,-43,-107,91,22,71,117,114,13,-60,52,-120,82,16,-109,84,-90,48,22,124,0,-64,-31,-87,-39,-17,62,-19,79,-37,-78,-66,97,15,117,-19,-110,81,28,50,-86,-19,-74,-57,-53,56,13,-104,50,38,-60,-12,114,-67,-68,122,88,44,-81,-106,-12,10,66,52,-48,-52,74,49,118,4,10,81,-112,80,16,-93,72,58,-117,96,-65,-32,-43,-10,-77,8,117,-42,28,-69,-22,-76,-1,-25,112,-29,75,115,-84,-88,72,82,81,120,13,-109,107,12,81,-24,-39,91,52,14,82,-33,124,-13,127,-101,-86,107,69,110,-69,93,117,-32,-125,-94,-22,126,-37,-77,36,123,38,38,-87,111,-30,24,-31,-44,27,-14,-40,28,34,81,-40,-68,12,33,25,-22,-111,13,88,125,-91,-55,32,-110,-75,68,99,-119,-26,-93,94,39,81,-70,-84,-48,-116,-77,93,-34,-56,89,44,75,66,37,109,-102,44,-118,7,22,-61,88,44,-95,-110,124,-109,37,27,11,-57,-44,74,-62,93,-116,-116,-90,32,-24,66,39,77,-42,35,-86,25,-75,-75,-89,85,68,41,-74,23,105,-15,-18,-51,42,-25,-27,115,-97,-127,-4,-35,114,-107,23,-103,111,91,64,-24,33,-117,-118,91,-25,111,-90,25,88,-26,-85,-121,-17,-58,18,-28,-10,-42,-85,108,94,-36,-27,-85,124,62,-99,49,-45,-9,-7,116,-67,90,-52,103,12,123,-101,-21,-51,65,-104,-18,91,-40,-85,55,-7,60,-65,-29,6,10,-16,3,88,102,111,-39,-33,34,-56,-126,105,-32,109,105,17,63,-30,12,-121,-12,106,-12,83,40,-79,40,-73,-77,85,62,-99,-50,-2,-98,-125,-69,89,49,-51,-18,47,5,-17,16,-77,15,96,53,-5,-112,-81,50,48,-49,62,100,-85,85,-66,-50,-39,9,-115,-64,114,86,44,88,-27,22,-39,28,-52,-118,-39,-19,-54,-45,-113,-72,35,-78,33,-116,-10,-42,-7,-3,95,119,-117,-7,-30,-26,-53,37,-50,-7,78,-71,45,-69,118,-9,-76,61,-5,106,43,-124,62,66,-10,116,110,-69,-3,-97,-27,-82,5,-57,-74,-69,-112,47,104,-81,-7,122,127,20,53,125,-33,-98,43,15,7,-63,36,-64,-118,-90,58,-56,5,-81,28,-5,99,-105,-110,-18,109,-3,-115,-118,96,20,16,-49,68,-84,73,-63,43,-124,-40,15,64,-12,6,-46,27,-110,-116,9,-123,65,12,30,121,101,-47,-120,31,-37,65,-110,2,36,-50,-4,79,-125,10,-63,-108,11,47,-86,-109,48,-120,47,-85,49,-21,-124,108,75,94,82,-121,-84,49,-45,94,-115,66,38,34,-106,-98,68,-104,-97,112,66,-113,66,110,73,-22,-47,-32,53,-37,-125,-90,97,-92,-7,-20,87,14,30,123,-107,-46,95,-81,82,122,-53,-108,-106,-73,72,-13,-107,107,-3,-66,-110,80,26,69,-82,-81,126,-27,-32,-85,87,41,125,-11,42,-91,-81,76,41,125,69,-82,-81,92,59,-8,-118,76,95,-7,-67,-30,-94,-81,126,-27,-32,-85,87,41,125,-11,42,-91,-81,76,-7,-116,-81,92,107,-6,-22,61,-4,-20,-83,54,-34,93,-43,70,109,-58,-121,88,92,54,-43,14,111,116,-104,-46,-60,-119,-51,-111,86,-75,-3,-35,-116,15,-67,85,125,62,13,86,-21,-122,44,-50,120,-12,-87,-78,104,-58,7,-127,48,-26,-45,96,-75,110,-56,-30,-116,23,103,85,76,-51,-8,32,16,-58,124,26,-84,-42,13,89,28,-3,-28,-91,-29,-63,-33,-21,-116,-103,36,-96,-42,-7,58,122,92,33,85,-75,-115,-66,40,-75,44,-12,70,-82,111,-19,-84,106,-49,-47,-41,-50,94,33,-127,48,-42,-63,-50,118,63,-125,-98,83,59,115,2,97,-28,-57,-56,2,37,70,-72,118,92,-84,-40,26,61,-56,90,54,-123,70,6,-24,-58,-43,115,84,-112,110,92,2,-95,5,-23,-58,-43,-49,-96,-126,116,-29,18,8,45,72,43,46,-95,-106,17,-70,113,-123,88,-117,-117,97,107,-39,64,26,25,-96,38,-47,72,110,-76,61,66,-123,94,91,115,40,-104,-101,7,-127,-48,-110,82,-101,86,53,-104,-101,-95,-34,87,-107,-82,-38,-76,-86,-63,-36,-36,9,-124,-106,-56,-38,-76,-86,-63,-84,-84,-122,-14,-38,-37,-77,12,123,18,-32,-69,-80,-46,4,-102,47,67,-77,-7,-35,98,-11,46,19,23,54,126,105,93,-84,-13,-62,-67,28,68,-124,77,77,-27,-11,80,114,-7,-107,-59,115,99,65,-87,15,-4,112,122,42,-69,125,123,61,45,-69,-57,-46,-61,74,-93,-128,-67,-41,57,52,113,125,-71,68,-62,113,28,80,-20,-110,-90,-27,-31,-68,-33,-107,59,-49,29,41,74,124,-50,45,-69,-118,-67,69,-127,-121,-61,-2,-52,-99,116,121,-108,109,-91,-56,-27,-83,-37,115,-23,121,67,-91,-108,-65,120,82,58,-34,16,123,52,-126,-120,-3,-125,-44,-105,51,-44,23,118,108,-66,16,127,63,125,-56,-40,-115,-41,-77,34,24,-30,32,33,46,67,-36,-44,-82,-8,-91,-51,19,60,102,126,-123,-82,95,-66,-105,-48,-124,93,59,19,23,122,-38,55,63,-73,117,-5,-114,-67,-42,-17,74,24,64,-49,44,20,-94,-128,-67,123,126,13,85,-90,-127,80,51,-88,12,-7,110,-58,3,56,36,22,24,-5,111,-4,67,-27,-79,59,2,-37,49,72,124,-67,-22,-57,24,-117,-102,-107,82,49,82,50,-116,45,-28,88,-121,17,29,45,13,99,-95,87,88,83,110,-44,47,-110,-81,-51,-67,-50,-80,53,-24,107,-115,-83,99,11,35,18,-110,38,-54,63,-63,-30,94,-9,82,25,-109,30,-99,-114,52,35,-23,57,-54,-109,-38,-56,-107,46,31,59,96,18,3,-110,-120,-53,4,31,-91,-119,-100,61,-119,-122,-39,53,-103,-80,-82,112,86,20,-126,-95,34,-86,71,-92,41,-75,60,22,44,-27,125,109,-28,68,-105,-21,-71,31,88,124,36,116,-122,-83,65,95,-101,57,25,-79,-22,-36,96,58,126,-43,-116,-7,-37,-103,121,87,28,-104,49,81,62,8,-117,-125,111,-70,60,38,74,-82,-57,-110,68,110,-13,-58,-20,-8,-92,8,-101,53,37,-6,15,-24,-69,-105,-17,19,16,10,80,-20,-78,94,82,-60,-84,79,-30,72,81,101,-61,-5,-42,-45,-119,-72,99,-48,58,86,-118,-89,-97,123,-33,-100,79,85,30,55,-109,-120,95,-37,29,27,47,112,-109,-49,77,-110,-60,12,111,-33,28,-97,-86,-45,-103,81,-73,-66,-26,76,32,12,80,-22,-46,54,-81,-40,-63,5,-65,-39,-68,-66,-104,72,-121,-14,-14,68,-114,-44,47,36,-110,-60,-111,117,-62,44,-42,-39,61,-24,-65,-55,62,-13,65,-74,79,-95,-61,-66,-28,-96,-81,51,-118,61,-51,126,-97,-39,-45,84,-11,52,18,105,123,87,-105,83,-43,3,57,-58,-60,27,117,56,126,-70,-21,117,-58,28,-125,-66,-42,-40,58,-42,-24,64,4,-54,-50,66,101,-41,-18,101,50,-94,122,28,-23,56,-85,3,17,-43,29,123,-67,68,-102,82,43,35,4,-6,35,119,50,98,68,78,-96,47,70,37,37,-16,98,62,-116,14,-60,78,104,-33,106,13,62,-32,-2,-65,57,-92,-50,-23,38,68,125,-126,-45,-69,73,95,-79,-49,-108,-21,80,12,54,-5,127,20,3,37,47,43,6,98,53,-82,-37,-22,-36,-107,91,-2,1,-117,117,-108,26,-68,-80,-48,109,43,-84,-48,-15,23,10,-35,-90,-68,40,-74,52,-64,26,85,-58,118,117,33,-74,16,-6,10,-3,54,95,-2,93,-52,-42,-49,-108,-72,-51,123,89,-119,-65,-97,-4,7,16,9,66,-42,10,101,110,100,115,116,114,101,97,109,10,101,110,100,111,98,106,10,53,32,48,32,111,98,106,60,60,47,80,97,114,101,110,116,32,52,32,48,32,82,47,67,111,110,116,101,110,116,115,32,51,32,48,32,82,47,84,121,112,101,47,80,97,103,101,47,82,101,115,111,117,114,99,101,115,60,60,47,80,114,111,99,83,101,116,32,91,47,80,68,70,32,47,84,101,120,116,32,47,73,109,97,103,101,66,32,47,73,109,97,103,101,67,32,47,73,109,97,103,101,73,93,47,70,111,110,116,60,60,47,70,49,32,49,32,48,32,82,47,70,50,32,50,32,48,32,82,62,62,62,62,47,77,101,100,105,97,66,111,120,91,48,32,48,32,54,49,50,32,55,57,50,93,62,62,10,101,110,100,111,98,106,10,50,32,48,32,111,98,106,60,60,47,66,97,115,101,70,111,110,116,47,72,101,108,118,101,116,105,99,97,47,84,121,112,101,47,70,111,110,116,47,69,110,99,111,100,105,110,103,47,87,105,110,65,110,115,105,69,110,99,111,100,105,110,103,47,83,117,98,116,121,112,101,47,84,121,112,101,49,62,62,10,101,110,100,111,98,106,10,49,32,48,32,111,98,106,60,60,47,66,97,115,101,70,111,110,116,47,72,101,108,118,101,116,105,99,97,45,66,111,108,100,47,84,121,112,101,47,70,111,110,116,47,69,110,99,111,100,105,110,103,47,87,105,110,65,110,115,105,69,110,99,111,100,105,110,103,47,83,117,98,116,121,112,101,47,84,121,112,101,49,62,62,10,101,110,100,111,98,106,10,52,32,48,32,111,98,106,60,60,47,84,121,112,101,47,80,97,103,101,115,47,67,111,117,110,116,32,49,47,75,105,100,115,91,53,32,48,32,82,93,62,62,10,101,110,100,111,98,106,10,54,32,48,32,111,98,106,60,60,47,84,121,112,101,47,67,97,116,97,108,111,103,47,80,97,103,101,115,32,52,32,48,32,82,62,62,10,101,110,100,111,98,106,10,55,32,48,32,111,98,106,60,60,47,80,114,111,100,117,99,101,114,40,105,84,101,120,116,32,50,46,48,46,56,32,92,40,98,121,32,108,111,119,97,103,105,101,46,99,111,109,92,41,41,47,77,111,100,68,97,116,101,40,68,58,50,48,49,51,48,56,49,51,49,49,52,51,49,51,45,48,52,39,51,48,39,41,47,67,114,101,97,116,105,111,110,68,97,116,101,40,68,58,50,48,49,51,48,56,49,51,49,49,52,51,49,51,45,48,52,39,51,48,39,41,62,62,10,101,110,100,111,98,106,10,120,114,101,102,10,48,32,56,10,48,48,48,48,48,48,48,48,48,48,32,54,53,53,51,53,32,102,32,10,48,48,48,48,48,48,50,50,55,55,32,48,48,48,48,48,32,110,32,10,48,48,48,48,48,48,50,49,57,48,32,48,48,48,48,48,32,110,32,10,48,48,48,48,48,48,48,48,49,53,32,48,48,48,48,48,32,110,32,10,48,48,48,48,48,48,50,51,54,57,32,48,48,48,48,48,32,110,32,10,48,48,48,48,48,48,50,48,50,53,32,48,48,48,48,48,32,110,32,10,48,48,48,48,48,48,50,52,49,57,32,48,48,48,48,48,32,110,32,10,48,48,48,48,48,48,50,52,54,51,32,48,48,48,48,48,32,110,32,10,116,114,97,105,108,101,114,10,60,60,47,82,111,111,116,32,54,32,48,32,82,47,73,68,32,91,60,51,99,102,99,50,49,98,55,50,54,55,56,101,54,100,99,99,54,99,100,49,102,53,51,57,54,97,97,52,99,101,99,62,60,57,102,49,54,56,101,97,56,48,54,99,57,55,102,53,50,55,50,56,98,54,52,57,98,49,57,50,101,102,97,99,57,62,93,47,73,110,102,111,32,55,32,48,32,82,47,83,105,122,101,32,56,62,62,10,115,116,97,114,116,120,114,101,102,10,50,53,57,52,10,37,37,69,79,70,10];
					
//var_dump($bytes);
			//np_hoplite_byteArrayToFile($bytes,"pdf","leidy");	*/		


//	     np_hoplite_byteArrayToFile(file("http://online.novopayment.dev/Libro1.xlsx", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES), "xlsx", "hola"); 


	    $bytes[] = json_decode($this->input->post("bytes"));
	    $ext = $this->input->post("ext");
	    $nombreArchivo = $this->input->post("nombreArchivo");

	    np_hoplite_byteArrayToFile($bytes, $ext, $nombreArchivo);  

	}
	private function callWSMenuPorProducto($prefijo,$rif,$acCodCia,$codgrupoe,$pais){
			$this->lang->load('erroreseol');
		$canal = "ceo";
		$modulo="login";
		$function="login";
		$operation="menuPorProducto";
		$className="com.novo.objects.MO.ListadoMenuMO";
		$timeLog= date("m/d/Y H:i");
		$ip= $this->input->ip_address();
		$sessionId = $this->session->userdata('sessionId');
		$username = $this->session->userdata('userName');
		$token = $this->session->userdata('token');
		$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

		$menus = array(array(
			"app" => "EOL",
			"prod" => "$prefijo",
			"idUsuario"=>"$username",
			"idEmpresa"=>"$rif"
		));
		$estadistica = array(
			"producto" => array(
				"prefijo"=>"$prefijo",
				"rifEmpresa"=>"$rif",
				"acCodCia"=>"$acCodCia",
				"acCodGrupo" => "$codgrupoe"
				)
		);

		$data = array(
			"idOperation" => $operation,
			"className" => $className,
			"menus"=>$menus,
			"estadistica"=>$estadistica,
			"logAccesoObject"=>$logAcceso,
			"token"=>$token,
			"pais"=>$pais
		);
		$data = json_encode($data);
		print_r($data);
log_message('info','detalle produc '.$data);
		$dataEncry = np_Hoplite_Encryption($data);
		
		$data = array('bean' => $dataEncry, 'pais' =>$pais );		
		$data = json_encode($data);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS',$data);
		$jsonResponse = np_Hoplite_Decrypt($response);
		$response = json_decode($jsonResponse);
		print_r(json_encode($response));
		// if($response){
		// 	log_message('info','detalle produc '.$response->rc."/".$response->msg);
		// 	if($response->rc==0){
				
		// 		return $response;
		// 	}else{

		// 		if($response->rc==-61){
		// 			$this->session->sess_destroy();
		// 			$this->session->set_userdata('logged_in',false);
		// 			return $codigoError = array('ERROR' => '-29' );
		// 		}else{
		// 		$codigoError = lang('ERROR_('.$response->rc.')');
		// 		if(strpos($codigoError, 'Error')!==false){
		// 			$codigoError = array('ERROR' => lang('ERROR_GENERICO_USER') );
		// 		}else{
		// 			$codigoError = array('ERROR' => lang('ERROR_('.$response->rc.')') );
		// 		}
				
		// 		return $codigoError;}
		// 	}		
		// }else{
		// 	log_message('info','detalle produc NO WS');
		// 	return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER') );
		// }
}


	/**
 * [pantalla2 description]
 * @param  [type] $urlCountry [description]
 * @return [type]             [description]
 */
public function pantalla2($urlCountry){
	np_hoplite_countryCheck($urlCountry);
	$logged_in = $this->session->userdata('logged_in');
	$username = $this->session->userdata('userName');
   	$token = $this->session->userdata('token');




	//SIGUIENTE PANTALLA 
       // $responseMenuEmpresas =$this->callWSMenuEmpresa($username,20517372294,$token);
       // $responseMenuPorProducto =$this->callWSMenuPorProducto($username,$token,"C",20517372294);
       //$rTest1 = $this->callWSBuscarDepositoGarantia($username,$token);
       //
	      // $responseMenuEmpresas =$this->callWSMenuEmpresa($username,20517372294,$token);
	       
		
		


       $rTest = $this->callWSListaEmpresas($username,$token);


       $this->output->set_content_type('application/json');
$this->output->set_header('Cache-Control: no-cache, must-revalidate');
$this->output->set_header('Expires: '.date('r', time()+(86400*365)));

$output =$rTest;

$this->output->set_output($output); 
		
       /*
       $categoriasG = array();
	   $seriesG = array();
   		foreach ($rTest->listaGrafico AS $v) {
   			foreach ($v->categorias as $v2) {
   			  $categoriasG[]='"'.$v2->nombreCategoria.'"';
   			}
   			foreach ($v->series as $indice => $value) {
   				$seriesG[$indice]["name"] = $value->nombreSerie;
   				$seriesG[$indice]["data"] = $value->valores;
   			}
   		}

   		foreach ($seriesG as $key => $value) {
   			
   		}

   		$categoriasG = implode(",",$categoriasG);
   	    
       $FooterCustomJS=' function createChart() {
                    $("#chart").kendoChart({
                        theme: $(document).data("kendoSkin") || "default",
                        title: {
                            text: "Internet Users"
                        },
                        legend: {
                            position: "bottom"
                        },
                        chartArea: {
                            background: ""
                        },
                        series: [{
                            name: "World",
                            data: [15.7, 16.7, 20, 23.5, 26.6]
                        }, {
                            name: "United States",
                            data: [67.96, 68.93, 75, 74, 78]
                        }],
                        valueAxis: {
                            labels: {
                                format: "{0}%"
                            }
                        },
                        categoryAxis: {
                            categories: ['.$categoriasG.']
                        },
                        tooltip: {
                            visible: true,
                            format: "{0}%"
                        }
                    });
                }

                $(document).ready(function() {
                    setTimeout(function() {
                        // Initialize the chart with a delay to make sure
                        // the initial animation is visible
                        createChart();

                        $("#example").bind("kendo:skinChange", function(e) {
                            createChart();
                        });
                    }, 400);
                });
            ';

       	$datos['NombreCompleto'] =  $this->session->userdata('nombreCompleto');
       	$datos['Empresas'] = $responseListaEmpresas->empresas;
       	$this->output->enable_profiler(TRUE); 
*/
}

/**
 * [callWSBuscarDepositoGarantia description]
 * @param  [type] $username [description]
 * @param  [type] $token    [description]
 * @param  [type] $prefijo  [description]
 * @param  [type] $rif      [description]
 * @return [type]           [description]
 */
private function callWSBuscarDepositoGarantia($username,$token){
		$canal = "ceo";
		$modulo="login";
		$function="login";
		$operation="buscarDepositoGarantia";
		$className="com.novo.objects.MO.DepositosGarantiaMO";
		$timeLog= date("m/d/Y H:i");
		$ip= $this->input->ip_address();
		$username=trim($username);
		$idExtEmp="20517372707";
		$filtroFecha="1";
		$fechaIni="05/10/2010";
		$fechaFin="20/11/2010";
		$tamanoPagina=3;
		$paginaActual=1;
		$pais="pe";
		$producto="B";
		$nombreEmpresa="tebca";
		$descProd="Bonus Alimentacion";
		
		$logAcceso = np_hoplite_log($username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);


		$data = array(
			"idOperation" => $operation,
			"className" => $className,
			"idExtEmp"=>$idExtEmp,
			"filtroFecha"=>$filtroFecha,
			"fechaIni"=>$fechaIni,
			"fechaFin"=>$fechaFin,
			"tamanoPagina"=>$tamanoPagina,
			"paginaActual"=>$paginaActual,
			"pais"=>$pais,
			"producto"=>$producto,
			"nombreEmpresa"=>$nombreEmpresa,
			"descProd"=>$descProd,			
			"logAccesoObject"=>$logAcceso,
			"token"=>$token
		);
		$data = json_encode($data);
		$dataEncry = np_Hoplite_Encryption($data);
		
		$response = np_Hoplite_GetWS('eolwebInterfaceWS',$dataEncry);
		$jsonResponse = np_Hoplite_Decrypt($response);
		$response = json_decode($jsonResponse);
	
		if($response->rc==0){
			return $response;
		}else{
			return FALSE;
		}
		
		
}
/**
 * [callWSbuscarLotesPorAutorizar description]
 * @param  [type] $username [description]
 * @param  [type] $token    [description]
 * @return [type]           [description]
 */
private function callWSbuscarLotesPorAutorizar($username,$token){
		$canal = "ceo";
		$modulo="login";
		$function="login";
		$operation="buscarLotesPorAutorizar";
		$className="com.novo.objects.TOs.LoteTO";
		$timeLog= date("m/d/Y H:i");
		$ip= $this->input->ip_address();
		$username=trim($username);
		$accodcia="25";
		$accodgrupo="EMPRESA003";
		$actipoproducto="B";
		$logAcceso = np_hoplite_log($username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);



		$data = array(
			"idOperation" => $operation,
			"className" => $className,
			"accodcia"=>$accodcia,
			"accodgrupo"=>$accodgrupo,
			"actipoproducto"=>$actipoproducto,
			"logAccesoObject"=>$logAcceso,
			"token"=>$token
		);
		$data = json_encode($data);
		$dataEncry = np_Hoplite_Encryption($data);
		
		
		$response = np_Hoplite_GetWS('eolwebInterfaceWS',$dataEncry);
		$jsonResponse = np_Hoplite_Decrypt($response);
		$response = json_decode($jsonResponse);
		
		
		/*
		if($response->rc==0){
			return $response;
		}else{
			return FALSE;
		}
		*/
		
}

/**
 * [callWSbuscarTarjetasEmitidas description]
 * @param  [type] $username [description]
 * @param  [type] $token    [description]
 * @return [type]           [description]
 */

private function callWSbuscarTarjetasEmitidas($username,$token){
		$canal = "ceo";
		$modulo="login";
		$function="login";
		$operation="buscarTarjetasEmitidas";
		$className="com.novo.objects.MO.ListadoEmisionesMO";
		$timeLog= date("m/d/Y H:i");
		$ip= $this->input->ip_address();
		$username=trim($username);
		$accodcia="76";
		$fechaIni="01/01/2009";
		$fechaFin="16/04/2014";
		$tipoConsulta="0";
		$logAcceso = np_hoplite_log($username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

		$usuarioJ = array(
			"userName"=>$username
			);

		$data = array(
			"idOperation" => $operation,
			"className" => $className,
			"accodcia"=>$accodcia,
			"fechaIni"=>$fechaIni,
			"fechaFin"=>$fechaFin,
			"tipoConsulta"=>$tipoConsulta,
			"usuario"=>$usuarioJ,
			"logAccesoObject"=>$logAcceso,
			"token"=>$token
		);
		$data = json_encode($data);
		$dataEncry = np_Hoplite_Encryption($data);
		
		$response = np_Hoplite_GetWS('eolwebInterfaceWS',$dataEncry);
		$jsonResponse = np_Hoplite_Decrypt($response);
		$response = json_decode($jsonResponse);
		if($response->rc==0){
			return $response;
		}else{
			return FALSE;
		}





}
}
