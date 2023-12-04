<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('chatroom');
    }

    public function login()
    {
        if ($this->session->get('username') != null) {
            return redirect()->to('chat');
        }

        return view('login');
    }

    public function registerUsername()
    {
        $username = $this->request->getPost('username');
        $this->session->set('username', $username);
        return redirect()->to('chat');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('login');
    }
}
