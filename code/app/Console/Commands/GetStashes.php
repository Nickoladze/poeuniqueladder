<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Account;
use App\UniqueItem;

class GetStashes extends Command
{
	protected $signature = "get:stashes";
	protected $description = "Pull Latest from the river";

	public function __construct()
	{
		parent::__construct();
	}

	public function handle()
	{
		$this->getPage("25502018-27199341-25460605-26590582-27014237");

		// TODO
		// mark leagues when uniques were released
		// mark retired/renamed uniques
		// mark uniques hidden in unique tab by default
		// mark foil items, different frame border?
		// handle variants that aren't on different bases like atziri belt, beachhead, abyss uniques with 1/2 sockets
	}

	private function getPage($changeId = null)
	{
		$this->info("Getting page: {$changeId}");

		$response = Http::withHeaders([
			"User-Agent" => "OAuth poeuniqueladder/1.0.0 (contact: nickoladze@gmail.com) StrictMode"
		])->get("https://api.pathofexile.com/public-stash-tabs", [
			"id" => $changeId
		]);

		$body = $response->json();

		if(isset($body["stashes"]))
		{
			foreach($body["stashes"] as $stashChange)
			{
				$this->processStashChange($stashChange);
			}
		}

		$limited = $this->isNearRateLimit($response->headers());
		if($limited)
			sleep(1);

		if(!empty($body["next_change_id"]))
			$this->getPage($body["next_change_id"]);
	}

	private function isNearRateLimit($headers)
	{
		$ruleName = $headers["X-Rate-Limit-Rules"][0];

		$rule = $headers["X-Rate-Limit-{$ruleName}"][0];
		$ruleParts = explode(":", $rule);
		$state = $headers["X-Rate-Limit-{$ruleName}-State"][0];
		$stateParts = explode(":", $state);

		$periodRatio = $ruleParts[1] / $stateParts[1];
		$requestsInPeriod = $stateParts[0] * $periodRatio;

		return $requestsInPeriod + 1 > $ruleParts[0];
	}

	private function processStashChange($stashChange)
	{
		if(!$stashChange["public"]
			|| empty($stashChange["items"]))
			return;

		$account = $this->getAccount($stashChange["accountName"]);
		
		foreach($stashChange["items"] as $item)
		{
			$this->processItem($item, $account);
		}
	}

	private function processItem($item, $account)
	{
		if(!$item["identified"]
			|| $item["frameType"] != 3
			|| empty($item["name"]))
			return;

		$uniqueItem = $this->getUniqueItem($item);

		$account->uniqueItems()->syncWithoutDetaching([$uniqueItem->id]);
	}

	private function getAccount($name)
	{
		$account = Account::where("name", $name)->first();
		if(!$account)
		{
			$account = new Account;
			$account->name = $name;
			$account->save();
		}

		return $account;
	}

	private function getUniqueItem($item)
	{
		$uniqueItem = UniqueItem::where("name", $item["name"])
			->where("base_type", $item["baseType"])
			->first();

		if(!$uniqueItem)
		{
			$uniqueItem = new UniqueItem;
			$uniqueItem->name = $item["name"];
			$uniqueItem->icon = $item["icon"];
			$uniqueItem->base_type = $item["baseType"];

			if(isset($item["flavourText"]))
				$uniqueItem->flavor_text = implode("<br />", $item["flavourText"]);

			$uniqueItem->save();
		}

		return $uniqueItem;
	}
}
