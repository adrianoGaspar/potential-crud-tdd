<?php

namespace App\Http\Controllers;

use App\Models\Developer;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Object_;

class DeveloperController extends Controller
{
    private $developer;

    /**
     * DeveloperController constructor.
     * @param Developer $developer
     */
    public function __construct(Developer $developer)
    {
        $this->developer = $developer;
    }

    /**
     * get
     *
     * @param  mixed $id
     * @return void
     */
    public function get(Request $request, $id = null)
    {
        $query = $this->developer->query();
        // Filtra pelo ID se passado
        if ($id) $query = $this->developer->where('id', $id);

        // Filtra pela querystring se passada
        $queryString = $request->query();
        if (isset($queryString['search'])) $query = $this->developer->where('name', 'LIKE', $queryString['search'].'%');

        // Pagina o resultado
        $developers = $query->paginate(10);
        if ($developers)
            return response()->json($developers, 200)->withCallback();
        return response()->json('NOT_FOUND', 404)->withCallback();
    }

    /**
     * store
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $developer = $this->developer->create($request->all());
        if ($developer)
            return response()->json($developer, 201)->withCallback();
        return response()->json('OOPS', 400)->withCallback();
    }

    /**
     * Update
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $developer = $this->developer->where('id', $id)->first();

        if($developer and $developer->fill($request->all())->save())
            return response()->json($developer, 200)->withCallback();
        return response()->json('OOPS', 400)->withCallback();
    }

    /**
     * destroy
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        $developer = $this->developer->where('id', $id)->first();

        if($developer->delete())
            return response()->json('', 204)->withCallback();
        return response()->json('OOPS', 400)->withCallback();
    }
}
