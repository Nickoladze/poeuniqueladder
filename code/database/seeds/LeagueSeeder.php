<?php

use Illuminate\Database\Seeder;
use App\League;
use Faker\Generator as Faker;

class LeagueSeeder extends Seeder
{
	public function run(Faker $faker)
	{
		League::truncate();

		League::create([
			"name" => "Base Game",
		]);

		League::create([
			"name" => "Onslaught / Anarchy",
		]);

		League::create([
			"name" => "Nemesis / Domination",
		]);

		League::create([
			"name" => "Invasion / Ambush",
		]);

		League::create([
			"name" => "Beyond / Rampage",
		]);

		League::create([
			"name" => "Bloodlines / Torment",
		]);

		League::create([
			"name" => "Tempest / Warbands",
		]);

		League::create([
			"name" => "Talisman",
		]);

		League::create([
			"name" => "Perandus",
		]);

		League::create([
			"name" => "Prophecy",
		]);

		League::create([
			"name" => "Essence",
		]);

		League::create([
			"name" => "Breach",
		]);

		League::create([
			"name" => "Leagacy",
		]);

		League::create([
			"name" => "Harbinger",
		]);

		League::create([
			"name" => "Abyss",
		]);

		League::create([
			"name" => "Bestiary",
		]);

		League::create([
			"name" => "Incursion",
		]);

		League::create([
			"name" => "Delve",
		]);

		League::create([
			"name" => "Betrayal",
		]);

		League::create([
			"name" => "Synthesis",
		]);

		League::create([
			"name" => "Legion",
		]);

		League::create([
			"name" => "Blight",
		]);

		League::create([
			"name" => "Metamorph",
		]);

		League::create([
			"name" => "Delirium",
		]);

		League::create([
			"name" => "Harvest",
		]);

		League::create([
			"name" => "Heist",
		]);

		League::create([
			"name" => "Ritual",
		]);

		League::create([
			"name" => "Ultimatum",
		]);

		League::create([
			"name" => "Expedition",
		]);

		League::create([
			"name" => "Scourge",
		]);
	}
}
