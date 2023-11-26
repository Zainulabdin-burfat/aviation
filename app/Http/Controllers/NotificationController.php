<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function list()
    {
        return view('content.notifications.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $columns = [
            'notifications.id',
            'notifications.user_id',
            'notifications.title',
            'notifications.description',
            'notifications.menu',
            'notifications.created_by',
            'users.first_name as first_name',
            'users.last_name as last_name',
            'u2.first_name as u_first_name',
            'u2.last_name as u_last_name',
        ];

        $totalData = Notification::count();

        $query = Notification::select($columns)
            ->join('users', 'notifications.created_by', '=', 'users.id')
            ->join('users as u2', 'notifications.user_id', '=', 'u2.id');

        $start = $request->input('start');

        // Apply search filters
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($query) use ($search, $columns) {
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        // Total filtered records
        $totalFiltered = $query->count();

        // Order and pagination
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $query->offset($start)
            ->limit($request->input('length'))
            ->orderBy($order, $dir);


        $data = [];

        if (!empty($query)) {
            // providing a dummy id instead of database ids
            $ids = $start;

            foreach ($query->get() as $record) {
                $nestedData['id'] = $record->id;
                $nestedData['fake_id'] = ++$ids;
                $nestedData['user_id'] = $record->user_id;
                $nestedData['title'] = $record->title;
                $nestedData['description'] = $record->description;
                $nestedData['menu'] = $record->menu;
                $nestedData['created_by'] = $record->created_by;

                $data[] = $nestedData;
            }
        }

        if ($data) {
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'code' => 200,
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'message' => 'Internal Server Error',
                'code' => 500,
                'data' => [],
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }
}
