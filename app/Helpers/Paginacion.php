<?php

namespace App\Helpers;

class Paginacion
{

	public static function get($elements_count , $pagina , $elementos_por_pagina = 9)
	{
		$maximo_paginas = 5;

		$paginas_total = ceil($elements_count / $elementos_por_pagina);

		if($pagina > $paginas_total)
		{
			$pagina = $paginas_total;
		}

		if($pagina == 0) $pagina = 1;

		if($pagina != 0)
			$offset = $elementos_por_pagina * ($pagina -1);
		else
			$offset = $elementos_por_pagina * $pagina;

		$pagina_inicio = $pagina - 2;
		if($pagina_inicio  < 1 ) $pagina_inicio = 1;


		$pagina_anterior = $pagina -1;
		$pagina_siguiente =  $pagina + 1;
		if($pagina_anterior < 1 ) $pagina_anterior = $pagina;
		if($pagina_siguiente > $paginas_total ) $pagina_siguiente = $pagina;

		if($paginas_total == 0)
		{
			$pagina_anterior = 0;
			$pagina_siguiente =  0;
		}

		$paginacion = array(
			"pagina" => $pagina,
			"paginas" => array(),
			"pagina_anterior" => $pagina_anterior,
			"pagina_siguiente" => $pagina_siguiente,
			"offset" => $offset,
            "elementos_pagina" => $elementos_por_pagina,
            "total" => $elements_count
		);

		for($i = $pagina_inicio; $i <= $paginas_total ; $i++){
			if($maximo_paginas <= 0) break;
			$paginacion['paginas'][] = $i;
			$maximo_paginas--;
		}

		return $paginacion;
	}

}
