<?php

// Dados de autenticação
$un = 'rodrigo_palmeira';
$pw = 'Mautic@2022';

$hash = base64_encode($un.':'.$pw);

// Executa a Query
$limit = 100;
$offset = 0;
$total = 200000;
$updatedCount = 0;
$createdCount = 0;
$errorCount = 0;
$error400Count = 0;
$error500Count = 0;
$date = date('d-m-y H:i:s');

while( $offset < $total ) {
    error_log("Initializing query... ".$date."\n", 3, "/var/www/html/mautic/integrations/exports/logs/error.log");
    $big_query = "bq query --nouse_legacy_sql --format=csv 'SELECT * FROM vli-integrarodo-prd.sdx_mautic.dw_mautic_truckdriver LIMIT '". $limit ."' OFFSET '". $offset ."' ' >> exports/export.csv";
    $exec = shell_exec( $big_query );
    error_log("Query executed! \n", 3, "/var/www/html/mautic/integrations/exports/logs/error.log");
    // var_dump($exec);

    // Carrega o CSV
    $handle = fopen("exports/export.csv", "r");
    $row = 0;

    // Percorre o Array
    while ($line = fgetcsv($handle, 1000, ",")) {
        if ($row++ == 0) {
            continue;
        }
        
        $contact[] = [
            'truck_driver_id'       		=> $line[0],
            'cpf'                 		=> $line[1],
            'firstname'           	 	=> $line[2],
            'phone'                		=> $line[3],
            'email'                		=> $line[4],
            'tp_cadastro'          		=> $line[5],
            'dt_nascimento'        		=> $line[6],
            'status_cadastro'      		=> $line[7],
            'dt_att_cadastro'      		=> $line[8],
            'days_last_register'   		=> $line[9],
            'event_name'           	 	=> $line[10],
            'event_timestamp'      		=> $line[11],
            'dias_ult_atv_app'     	 	=> $line[12],
            'tipo_motorista'        		=> $line[13],
            'estado'                		=> $line[14],
            'geo_1a_cidade'         		=> $line[15],
            'geo_2a_cidade'         		=> $line[16],
            'geo_3a_cidade'         		=> $line[17],
            'geo_1o_estado'         		=> $line[18],
            'geo_2o_estado'         		=> $line[19],
            'geo_3o_estado'         		=> $line[20],
            'geo_1o_terminal'       		=> $line[21],
            'geo_2o_terminal'       		=> $line[22],
            'geo_3o_terminal'       		=> $line[23],
            'id_last_agenda'        		=> $line[24],
            'tp_carga'              		=> $line[25],
            'dt_last_agenda'        		=> $line[26],
            'tp_last_agenda'        		=> $line[27],
            'status'		    		=> $line[28],
            'terminal_last_agenda'  		=> $line[29],
            'cidade_last_agenda'    		=> $line[30],
            'estado_last_agenda'  		=> $line[31],
            "dias_ult_busc"       		=> $line[32],
            "flg_sem_filtro"      		=> $line[33],
            "cidade_destino_preferido"  	=> $line[34],
            "uf_destino_preferido"  		=> $line[35],
            "estado_destino_preferido"  	=> $line[36],
            "produto_destino_preferido" 	=> $line[37],
            "tipo_destino_preferido"    	=> $line[38],
            "dt_criacao_destino_preferido" 	=> $line[39],
            "cidade_origem_preferida"   	=> $line[40],
            "uf_origem_preferida"   		=> $line[41],
            "estado_origem_preferida"   	=> $line[42],
            "produto_origem_preferida"  	=> $line[43],
            "tipo_origem_preferida"     	=> $line[44],
            "dt_criacao_origem_preferida"	=> $line[45],
            "flg_abandono"              	=> $line[46],
            "flg_log"                   	=> $line[47],
            "flg_abandono_openco"    		=> $line[48],
	    "flg_abandono_bv"           	=> $line[49],
	    "flg_abandono_filtro"		=> $line[50],
	    "flg_abandono_cardfrete"		=> $line[51],
	    "terminal_destino_preferido"	=> $line[52],
	    "engagement"			=> $line[53],
	    "flg_frete_similar"			=> $line[54],
	    "transportadora_fav"		=> $line[55],
	    "flg_agend_acesso"			=> $line[56]
        ];
    }

    // print_r($contact);
    fclose($handle);

    // Atualiza os contatos
    $lines = count( $contact );
    $rows = $offset;


    while( $rows < $lines ) {
        
        $content = array(
            "truck_driver_id"     		=> $contact[$rows]['truck_driver_id'],
            "cpf"                 		=> $contact[$rows]['cpf'],
            "firstname"           		=> $contact[$rows]['firstname'],
            "phone"               		=> $contact[$rows]['phone'],
            "email"               		=> $contact[$rows]['email'],
            "tp_cadastro"         		=> $contact[$rows]['tp_cadastro'],
            "dt_nascimento"       		=> $contact[$rows]['dt_nascimento'],
            "status_cadastro"     		=> $contact[$rows]['status_cadastro'],
            "dt_att_cadastro"     		=> $contact[$rows]['dt_att_cadastro'],
            "days_last_register"  		=> $contact[$rows]['days_last_register'],
            "event_name"          		=> $contact[$rows]['event_name'],
            "event_timestamp"     		=> $contact[$rows]['event_timestamp'],
            "dias_ult_atv_app"    		=> $contact[$rows]['dias_ult_atv_app'],
            "tipo_motorista"      		=> $contact[$rows]['tipo_motorista'],
            "estado"              		=> $contact[$rows]['estado'],
            "geo_1a_cidade"       		=> $contact[$rows]['geo_1a_cidade'],
            "geo_2a_cidade"       		=> $contact[$rows]['geo_2a_cidade'],
            "geo_3a_cidade"       		=> $contact[$rows]['geo_3a_cidade'],
            "geo_1o_estado"      		=> $contact[$rows]['geo_1o_estado'],
            "geo_2o_estado"      		=> $contact[$rows]['geo_2o_estado'],
            "geo_3o_estado"       		=> $contact[$rows]['geo_3o_estado'],
            "geo_1o_terminal"     		=> $contact[$rows]['geo_1o_terminal'],
            "geo_2o_terminal"     		=> $contact[$rows]['geo_2o_terminal'],
            "geo_3o_terminal"     		=> $contact[$rows]['geo_3o_terminal'],
            "id_last_agenda"      		=> $contact[$rows]['id_last_agenda'],
            "tp_carga"            		=> $contact[$rows]['tp_carga'],
            "dt_last_agenda"      		=> $contact[$rows]['dt_last_agenda'],
            "tp_last_agenda"      		=> $contact[$rows]['tp_last_agenda'],
            "status_last_agenda"  		=> $contact[$rows]['status'],
            "terminal_last_agenda"		=> $contact[$rows]['terminal_last_agenda'],
            "cidade_last_agenda"  		=> $contact[$rows]['cidade_last_agenda'],
            "estado_last_agenda"  		=> $contact[$rows]['estado_last_agenda'],
            "estado_last_agenda"  		=> $contact[$rows]['estado_last_agenda'],
            "dias_ult_busc"       		=> $contact[$rows]['dias_ult_busc'],
            "flg_sem_filtro"      		=> $contact[$rows]['flg_sem_filtro'],
            "cidade_destino_preferido"  	=> $contact[$rows]['cidade_destino_preferido'],
            "uf_destino_preferido"  		=> $contact[$rows]['uf_destino_preferido'],
            "estado_destino_preferido"  	=> $contact[$rows]['estado_destino_preferido'],
            "produto_destino_preferido" 	=> $contact[$rows]['produto_destino_preferido'],
            "tipo_destino_preferido"    	=> $contact[$rows]['tipo_destino_preferido'],
            "dt_criacao_destino_preferido"	=> $contact[$rows]['dt_criacao_destino_preferido'],
            "cidade_origem_preferida"   	=> $contact[$rows]['cidade_origem_preferida'],
            "uf_origem_preferida"   		=> $contact[$rows]['uf_origem_preferida'],
            "estado_origem_preferida"   	=> $contact[$rows]['estado_origem_preferida'],
            "produto_origem_preferida"  	=> $contact[$rows]['produto_origem_preferida'],
            "tipo_origem_preferida"     	=> $contact[$rows]['tipo_origem_preferida'],
            "dt_criacao_origem_preferida"	=> $contact[$rows]['dt_criacao_origem_preferida'],
            "flg_abandono"              	=> $contact[$rows]['flg_abandono'],
            "flg_log"                   	=> $contact[$rows]['flg_log'],
            "flg_abandono_openco"       	=> $contact[$rows]['flg_abandono_openco'],
	    "flg_abandono_bv"           	=> $contact[$rows]['flg_abandono_bv'],
	    "flg_abandono_filtro"		=> $contact[$rows]['flg_abandono_filtro'],
	    "flg_abandono_cardfrete"		=> $contact[$rows]['flg_abandono_cardfrete'],
	    "terminal_destino_preferido"	=> $contact[$rows]['terminal_destino_preferido'],
	    "engagement"			=> $contact[$rows]['engagement'],
	    "flg_frete_similar"			=> $contact[$rows]['flg_frete_similar'],
	    "transportadora_fav"		=> $contact[$rows]['transportadora_fav'],
	    "flg_agend_acesso"			=> $contact[$rows]['flg_agend_acesso'],
            "ipAddress"             		=> "192.168.0.1",
            "overwriteWithBlank"    		=> true,
        );
        
	    // print_r("contato processado\n");
        $rows++;
        
        $p = json_encode( $content );

        // Inicializa o CURL
        $ch = curl_init('https://34.134.25.70/api/contacts/new');

	$authorization = 'Authorization: Basic ' . $hash;
        
        curl_setopt( $ch, CURLOPT_HEADER, 1 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization ) );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $p );
        
        // Executa o CURL
        $res = curl_exec( $ch );
	// Log Metrics
	$info = curl_getinfo( $ch );

	if($info['http_code'] == 200) $updatedCount++;
	if($info['http_code'] == 201) $createdCount++;
	if($info['http_code'] != 200 && $info['http_code'] != 201) $errorCount++;
	if($info['http_code'] == 400) $error400Count++;
	if($info['http_code'] == 500) $error500Count++;

	$dateLog = date('d-m-y H:i:s');
	error_log("Updated: ".$updatedCount." | Created: ".$createdCount." | Total Errors: ".$errorCount." | 400: ".$error400Count." | 500: ".$error500Count." | Date: ".$dateLog." | STATUS: ".$info['http_code']."\n", 3, "/var/www/html/mautic/integrations/exports/logs/error.log");
	        
        // if(curl_error( $ch )) {
        //     var_dump( curl_error( $ch ) );
        // }

        curl_close( $ch );

        //echo $rows . ' - ';
	//echo $offset . ' - ';
	//echo $lines . ' - ';
    }

    $offset = $offset + 100;

    // Abre arquivo para escrita
    $file = fopen('exports/export.csv', 'w');
    // Esvazia o arquivo temporário
    fputcsv($file, []);
    fclose($file);

}

error_log("Last contact: ". $offset ." \n", 3, "/var/www/html/mautic/integrations/exports/logs/error.log");

?>
