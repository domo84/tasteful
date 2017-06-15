<?php

trait provider
{
	public function examples()
	{
		return [
			[["_id" => 1, "name" => "Name#1", "content" => "Content#1"]],
			[["_id" => 2, "name" => "Name#2", "content" => "Content#2"]]
		];
	}

	public function moreExamples()
	{
		return [
			[["_id" => 1, "name" => "New_Name#1", "content" => "New_Content#1"]],
			[["_id" => 2, "name" => "New_Name#2", "content" => "New_Content#2"]]
		];
	}

	public function faultyExamples()
	{
		return [
			[["_id" => 601, "name" => "cider"]],
			[["_id" => 602, "name" => "cider", "body" => "moo"]],
			[["_id" => 603, "content" => "cider"]],
			[["_id" => 604, "content" => "cider", "body" => "moo"]]
		];
	}

	public function items()
	{
		return [
			[["_id" => 1, "example_id" => 1, "name" => "Item#1"]],
			[["_id" => 2, "example_id" => 1, "name" => "Item#2"]]
		];
	}

	public function moreItems()
	{
		return [
			[["_id" => 1, "example_id" => 1, "name" => "new_name#1"]],
			[["_id" => 2, "example_id" => 1, "name" => "il_nome#2"]]
		];
	}

	public function faultyItems()
	{
		return [
			[["_id" => 500, "nome" => "Hest"]],
			[["_id" => 501, "gnome" => "kde"]]
		];
	}
}
