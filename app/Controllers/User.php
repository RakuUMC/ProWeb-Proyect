<?php

namespace App\Controllers;

class User extends BaseController
{
    public function profile()
    {
        $session = session();
        if (!$session->has('user')) {
            return redirect()->to('login');
        }

        $user = $session->get('user');

        // Inicializar la lista de direcciones en la sesión del usuario si no existe
        if (!isset($user['addresses'])) {
            $user['addresses'] = [];
            // Si el usuario tiene una dirección de registro, usarla como 'Principal'
            if (isset($user['address'])) {
                $user['addresses'][] = [
                    'alias' => 'Principal',
                    'address' => $user['address']
                ];
            }
            // Actualizar sesión para que se mantenga
            $session->set('user', $user);
        }

        // Obtener pedidos de la base de datos
        $orderModel = new \App\Models\OrderModel();
        $orderItemModel = new \App\Models\OrderItemModel();
        
        $userId = $user['id'] ?? 0;
        $dbOrders = $orderModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll();
        
        $orders = [];
        foreach ($dbOrders as $o) {
            // items del pedido
            $items = $orderItemModel->where('order_id', $o['id'])->findAll();
            $itemSummary = [];
            foreach ($items as $item) {
                $itemSummary[] = $item['quantity'] . 'x ' . $item['product_name'];
            }
            
            $orders[] = [
                'id' => $o['id'],
                'date' => date('d M Y, h:i A', strtotime($o['created_at'])),
                'total' => $o['total'],
                'status' => ucfirst($o['status']),
                'items' => implode(', ', $itemSummary),
                'link_invoice' => base_url('order/invoice/' . $o['id']),
                'link_tracking' => base_url('order/tracking/' . $o['id'])
            ];
        }

        $data = [
            'user' => $user,
            'orders' => $orders,
            'addresses' => $user['addresses']
        ];

        return view('user/profile', $data);
    }

    public function add_address()
    {
        $session = session();
        if (!$session->has('user')) {
             return redirect()->to('login');
        }

        $alias = $this->request->getPost('alias');
        $address = $this->request->getPost('address');

        if ($alias && $address) {
            $user = $session->get('user');
            
            // Asegurar que el array de direcciones existe
            if (!isset($user['addresses'])) {
                $user['addresses'] = [];
            }

            // Agregar nueva dirección
            $user['addresses'][] = [
                'alias' => $alias,
                'address' => $address
            ];

            // Guardar en la sesión
            $session->set('user', $user);

            return redirect()->to('profile')->with('success', 'Dirección agregada correctamente.');
        }

        return redirect()->to('profile')->with('error', 'Por favor completa todos los campos.');
    }

    public function remove_address()
    {
        $session = session();
        if (!$session->has('user')) {
             return redirect()->to('login');
        }

        $index = (int) $this->request->getPost('index');
        $user = $session->get('user');

        if (isset($user['addresses'][$index])) {
            array_splice($user['addresses'], $index, 1);
            $session->set('user', $user);
            return redirect()->to('profile')->with('success', 'Dirección eliminada.');
        }

        return redirect()->to('profile')->with('error', 'No se pudo eliminar la dirección.');
    }

    public function login()
    {
        if (session()->has('user')) {
            return redirect()->to('profile');
        }
        return view('user/login');
    }

    public function register_view()
    {
        if (session()->has('user')) {
            return redirect()->to('profile');
        }
        return view('user/register');
    }

    public function create()
    {
        $userModel = new \App\Models\UserModel();
        
        $username = $this->request->getPost('username');
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $username,
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
        ];

        // Verificación básica
        if ($userModel->where('email', $username)->first()) {
             return redirect()->back()->with('error', 'El nombre de usuario ya está en uso.');
        }

        $userModel->save($data);
        
        // Inicio de sesión automático
        $user = $userModel->where('email', $username)->first();
        // Agregar avatar para compatibilidad con la vista
        $user['avatar'] = 'https://ui-avatars.com/api/?background=random&color=fff&name=' . urlencode($user['name']);
        
        session()->set('user', $user);
        return redirect()->to('profile');
    }

    public function attempt_login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('email', $username)->first();

        if ($user) {
             if (password_verify($password, $user['password_hash'])) {
                 $user['avatar'] = 'https://ui-avatars.com/api/?background=FF5722&color=fff&name=' . urlencode($user['name']);
                 session()->set('user', $user);
                 return redirect()->to('profile');
             }
        }
        
        return redirect()->to('login')->with('error', 'Usuario o contraseña incorrectos');
    }

    public function logout()
    {
        session()->remove('user');
        return redirect()->to('/');
    }
}
