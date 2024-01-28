<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedbak_reason;
use App\Http\Requests\StoreFeedbak_reasonRequest;
use App\Http\Requests\UpdateFeedbak_reasonRequest;

class FeedbakReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function get_list()
    {
        $faqs = Feedbak_reason::all();

        return response()->json(
            [
                'message'=>'faqs',
                'data'=> [
                    'feed_back_reasons'=> $faqs,
                ]
            ]
        );
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
     * @param  \App\Http\Requests\StoreFeedbak_reasonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFeedbak_reasonRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Feedbak_reason  $feedbak_reason
     * @return \Illuminate\Http\Response
     */
    public function show(Feedbak_reason $feedbak_reason)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Feedbak_reason  $feedbak_reason
     * @return \Illuminate\Http\Response
     */
    public function edit(Feedbak_reason $feedbak_reason)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFeedbak_reasonRequest  $request
     * @param  \App\Models\Feedbak_reason  $feedbak_reason
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFeedbak_reasonRequest $request, Feedbak_reason $feedbak_reason)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feedbak_reason  $feedbak_reason
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feedbak_reason $feedbak_reason)
    {
        //
    }
}
