<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->term;
        $limit = $request->limit;
        $offset = $request->offset;
        $price = $request->price;
        $open_now = $request->open_now;

        return response()->json([
            'data' => Business::when($term, function ($query, $term)  {
                $query->whereRaw('LOWER(name) LIKE ? ', ['%'.Str::lower($term).'%']);
            })->when($price, function ($query, $price)  {
                $query->where('price', $price);
            })->when($open_now, function ($query, $open_now)  {
                $query->where('is_closed', $open_now);
            })->when($limit, function ($query, $limit) use($offset) {
                $query->limit($limit);
                $query->when($offset, function ($query, $offset) use($limit) {
                    if ($limit > 1) {
                        $query->offset($offset);
                    }
                });
            })->orderBy('name', 'asc')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', Rule::unique('businesses', 'name')],
        ]);

        $model = new Business();
        $this->columns($model, $request);
        $model->save();

        return response()->json([
            'message' => 'Data berhasil disimpan.',
            'data' => $model
        ]);
    }

    public function update(Request $request)
    {
        if ($request->id) {
            try {
                $request->validate([
                    'name' => ['required', Rule::unique('businesses', 'name')->ignore(Crypt::decryptString($request->id))],
                ]);

                $model = Business::findOrFail(Crypt::decryptString($request->id));
                $this->columns($model, $request);
                $model->save();

                return response()->json([
                    'message' => 'Data berhasil diperbaharui.',
                    'data' => $model
                ]);
            } catch(DecryptException $e) {
                return response()->json([
                    'message' => 'Data tidak ditemukan.',
                ], 404);
            } catch(ModelNotFoundException $e) {
                return response()->json([
                    'message' => 'Data tidak ditemukan.',
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }
    }

    public function destroy(Request $request)
    {
        if ($request->id) {
            $model = Business::findOrFail(Crypt::decryptString($request->id));
            $model->delete();

            return response()->json([
                'message' => 'Data berhasil dihapus.',
                'data' => $model
            ]);
        } else {
            return response()->json([
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }
    }

    private function columns($model, $request)
    {
        $model->alias = Str::replace(' ', '-', Str::lower($request->name));
        $model->name = $request->name;
        $model->image_url = $request->image_url;
        $model->is_closed = $request->is_closed;
        $model->url = $request->url;
        $model->review_count = $request->review_count;
        $model->rating = $request->rating;
        $model->price = $request->price;
        $model->phone = $request->phone;
        $model->display_phone = $request->display_phone;
        $model->distance = $request->distance;
    }
}
