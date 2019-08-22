<?php

class vt_data_gen{
	static function create_term($name, $taxonomy = 'category'){
		$obj = new stdClass();
		$obj->name = $name;
		$obj->slug = vtm_string_to_slug($name);
		$obj->term_id = date('YmdHis');
		return $obj;
	}
	static function get_terms($names, $taxonomy = 'category'){
		$objects = [];
		foreach ($names as $name){
			array_push($objects, self::create_term($name, $taxonomy));
		}
		return $objects;
	}
}