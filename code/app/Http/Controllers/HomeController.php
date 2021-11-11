<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;

class HomeController extends Controller
{
	public function index(Request $request)
	{
		$topAccounts = Account::select(\DB::raw("accounts.*, COUNT(account_unique_item.unique_item_id) AS total_uniques"))
			->join("account_unique_item", "account_unique_item.account_id", "accounts.id")
			->groupBy("accounts.id")
			->orderBy("total_uniques", "DESC")
			->limit(100)
			->get();

		return view("home", ["topAccounts" => $topAccounts]);
	}

	public function showAccount(Request $request, Account $account)
	{
		$uniqueItems = $account->uniqueItems()
			->orderBy("name", "ASC")
			->get();

		return view("account", ["account" => $account, "uniqueItems" => $uniqueItems]);
	}

	public function postFindAccount(Request $request)
	{
		$account = Account::where("name", $request->get("accountName"))->first();
		if($account)
			return redirect()->action("HomeController@showAccount", $account);

		return redirect()->back()->with("error", "Account Not Found");
	}
}
