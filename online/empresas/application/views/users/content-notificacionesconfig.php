<? if( ! $this->session->userdata('logged_in') ){redirect($urlBase);}
$pais = $this->uri->segment(1);
?>
<h1>Notificaciones</h1>
