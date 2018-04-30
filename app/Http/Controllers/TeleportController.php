<?php

namespace App\Http\Controllers;

use App\Teleport as Teleport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\View;
use Session;

class TeleportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('Hello from Teleport!');
        return view('teleport');
    }

    public function search(Request $req)
    {
        $searchQuery = $req->input('searchQuery');
        $searchQuery = rawurlencode($searchQuery);

        if ($searchQuery == '') {

            Session::flash('message', 'Brak miasta!');

        } else {

            try {

                Teleport::create(['status' => 'pending', 'city_search' => $searchQuery]);

                $lastInsertId = \Illuminate\Support\Facades\DB::getPdo()->lastInsertId();

                if ($lastInsertId != null) {
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.teleport.org/api/cities/?search=$searchQuery",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_TIMEOUT => 30000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => array(
                            // Set Here Your Requesred Headers
                            'Content-Type: application/json',
                            $searchQuery,

                        ),
                    ));
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);

                    if ($err || $response === false) {
                        // echo "cURL Error #:" . $err;
                        // status failed
                        \Illuminate\Support\Facades\DB::update('update teleports set status = ? where id = ?', ['failed', $lastInsertId]);
                        Session::flash('message', 'Błąd API!');
                    } else {

                        $arr = json_decode($response, true);
                        // status success
                        \Illuminate\Support\Facades\DB::update('update teleports set status = ?, query_result = ? where id = ?', ['success', $response, $lastInsertId]);
                        $url = $arr['_embedded']["city:search-results"][0]['_links']["city:item"]['href'];

                        $curl2 = curl_init();
                        curl_setopt_array($curl2, array(
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                        ));
                        $response2 = curl_exec($curl2);
                        $err2 = curl_error($curl2);
                        curl_close($curl2);
                        $arr += json_decode($response2, true);

                        return view('city')->with('arr', $arr);

                    }
                } else {
                    Session::flash('message', 'Błąd bazy danych! Spróbuj ponownie za chwile.');
                }
            } catch (\PDOException $e) {

                Session::flash('message', 'Błąd bazy danych! Spróbuj ponownie za chwile.');

            }
        }
        return view('teleport');
    }

    public function autocomplete(Request $req)
    {

        if (request()->ajax()) {
            $searchQuery = $request->input;
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.teleport.org/api/cities/?search=$searchQuery",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requested Headers
                'Content-Type: application/json',
                $searchQuery,

            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err || $response === false) {
            echo "cURL Error #:" . $err;
        } else {
            $arr = json_decode($response, true);
            $url = $arr['_embedded']["city:search-results"][0]['_links']["city:item"]['href'];

            $curl2 = curl_init();
            curl_setopt_array($curl2, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
            ));
            $response2 = curl_exec($curl2);
            $err2 = curl_error($curl2);
            curl_close($curl2);
            $arr += json_decode($response2, true);
            $georesult = array();
            foreach ($arr["_embedded"]["city:search-results"] as $key) {
                array_push($georesult, $key["matching_full_name"]);
            }
            return $georesult;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Teleport  $teleport
     * @return \Illuminate\Http\Response
     */
    public function show(Teleport $teleport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Teleport  $teleport
     * @return \Illuminate\Http\Response
     */
    public function edit(Teleport $teleport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Teleport  $teleport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teleport $teleport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Teleport  $teleport
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teleport $teleport)
    {
        //
    }
}
