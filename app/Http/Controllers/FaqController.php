<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq; 
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        $faq = Faq::all();
        return response()->json([
            'message' => 'Data retrieved successfully',
            'data'    => $faq,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:255',
            'answer'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data['question'] = $request->question;
        $data['answer']   = $request->answer;

        $faq = Faq::create($data);
        return response()->json([
            'status'  => true,
            'message' => 'Data created successfully',
            'data'    => $faq,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
