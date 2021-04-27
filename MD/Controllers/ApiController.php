<?php 

namespace Md\Controllers;

use Md\Db\Repository;

abstract class ApiController extends Controller
{
    public function __construct(IRouter $_router)
    {
        parent::__construct($_router);
    }

    public function handleRequest(): IResponse
    {
        if(empty($this->repo)) {
            Http::notImplemented();
        }

        $m = $this->request->getMethod();
        $a = $this->request->getAction();
        $id = $this->request->getId();

        switch($m)
        {
            case 'get':
                $this->get();
            break;
            case 'post':
                //Auth::sessionRequired();
                if($id !== null) {
                    Http::notFound();
                }
                $this->post();
            break;
            case 'put':
                //Auth::sessionRequired();
                if($id === null) {
                    Http::notFound();
                }
                $this->put();
            break;
            case 'delete':
                //Auth::sessionRequired();
                if($id === null) {
                    Http::notFound();
                }
                $this->delete();
            break;
            default:
                Http::notAllowed();
            break;
        }

        return $this->response;
    }

    protected function get()
    {
        if(empty($this->request->id)) {
            $data = $this->repo->getById($this->request->getId());
        }
        else {
            $data = $this->repo->getAll();
        }
    
        $this->response->setData($data);
    }

    protected function post()
    {
        $data = $this->request->getData();

        if(!$this->repo->validate($data)) {
            Http::badRequest();
        }

        $this->response->setCode(201);
        $this->response->setData(['added' => $data]);
    }

    protected function put()
    {
        $data = $this->request->getData();

        if(!$this->repo->exists($this->request->getId())) {
            Http::notFound();
        }

        if(!$this->repo->validate($data)) {
            Http::badRequest();
        }

        $this->response->setCode(202);
        $this->response->setData(['updated' => $data]);
    }

    protected function delete()
    {
        if(!$this->repo->exists($this->request->getId())) {
            Http::notFound();
        }

        $this->response->setCode(204);
        $this->response->setData(['deleted' => $this->request->getId()]);
    }
}