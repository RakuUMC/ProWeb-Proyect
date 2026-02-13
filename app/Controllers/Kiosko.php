<?php

namespace App\Controllers;

class Kiosko extends BaseController
{
    public function index()
    {
        $session = session();
        $savedPizzas = [];
        if ($session->has('user')) {
            $model = new \App\Models\SavedPizzaModel();
            $savedPizzas = $model->where('user_id', $session->get('user')['id'])->orderBy('created_at', 'DESC')->findAll();
        }

        // Definicion de cada ingrediente mas su precio e imagen
        $data = [
            'saved_pizzas' => $savedPizzas,
            'sizes' => [
                ['id' => 'size_personal', 'name' => 'Personal', 'price' => 5.00],
                ['id' => 'size_medium', 'name' => 'Mediana', 'price' => 8.00],
                ['id' => 'size_large', 'name' => 'Familiar', 'price' => 12.00],
            ],
            'sauces' => [
                ['id' => 'sauce_tomato', 'name' => 'Salsa de Tomate', 'price' => 0, 'image' => 'sauce_tomato.png', 'thumbnail' => 'sauce_tomato.png'],
                ['id' => 'sauce_bbq', 'name' => 'Salsa BBQ', 'price' => 1.50, 'image' => 'sauce_bbq.png', 'thumbnail' => 'sauce_bbq.png'],
                ['id' => 'sauce_white', 'name' => 'Salsa Blanca (Alfredo)', 'price' => 1.50, 'image' => 'sauce_white.png', 'thumbnail' => 'sauce_white.png'],
            ],
            'cheeses' => [
                ['id' => 'cheese_mozarella', 'name' => 'Mozzarella', 'price' => 0, 'image' => 'cheese_moz.png', 'thumbnail' => 'cheese_moz.png'],
                ['id' => 'cheese_cheddar', 'name' => 'Cheddar', 'price' => 2.00, 'image' => 'cheese_cheddar.png', 'thumbnail' => 'cheese_cheddar.png'],
                ['id' => 'cheese_parmesan', 'name' => 'Parmesano', 'price' => 2.50, 'image' => 'cheese_parmesan.png', 'thumbnail' => 'cheese_parmesan.png'],
            ],
            'meats' => [
                ['id' => 'top_pepperoni', 'name' => 'Pepperoni', 'price' => 2.00, 'image' => 'top_pepperoni.png', 'thumbnail' => 'top_pepperoni.png'],
                ['id' => 'top_ham', 'name' => 'Jamón', 'price' => 2.00, 'image' => 'top_ham.png', 'thumbnail' => 'top_ham.png'],
                ['id' => 'top_bacon', 'name' => 'Tocineta', 'price' => 2.50, 'image' => 'top_bacon.png', 'thumbnail' => 'top_bacon.png'],
                ['id' => 'top_chicken', 'name' => 'Pollo Frito', 'price' => 2.50, 'image' => 'top_chicken.png', 'thumbnail' => 'top_chicken.png'],
            ],
            'veggies' => [
                ['id' => 'top_mushrooms', 'name' => 'Champiñones', 'price' => 1.50, 'image' => 'top_mushrooms.png', 'thumbnail' => 'top_mushrooms.png'],
                ['id' => 'top_peppers', 'name' => 'Pimientos', 'price' => 1.00, 'image' => 'top_peppers.png', 'thumbnail' => 'top_peppers.png'],
                ['id' => 'top_onions', 'name' => 'Cebolla', 'price' => 1.00, 'image' => 'top_onions.png', 'thumbnail' => 'top_onions.png'],
                ['id' => 'top_olives', 'name' => 'Aceitunas', 'price' => 1.50, 'image' => 'top_olives.png', 'thumbnail' => 'top_olives.png'],
                ['id' => 'top_pineapple', 'name' => 'Piña', 'price' => 1.50, 'image' => 'top_pineapple.png', 'thumbnail' => 'top_pineapple.png'],
                ['id' => 'top_corn', 'name' => 'Maíz', 'price' => 1.00, 'image' => 'top_corn.png', 'thumbnail' => 'top_corn.png'],
            ]
        ];

        return view('kiosko/builder', $data);
    }

    public function addToCart()
    {
        // logica de agregar pizza personalizada al carrito
        $session = session();
        $cart = $session->get('cart') ?? [];
        
        $request = \Config\Services::request();
        
        $description = $request->getPost('description');
        // Convierte la descripcion "Masa Tradicional, Salsa X, Queso Y" en un array para 'extras'
        $extras = array_map('trim', explode(',', $description));
        $extras = array_filter($extras);

        $customPizza = [
            'name' => 'Pizza Personalizada',
            'price' => (float) $request->getPost('total_price'),
            'qty' => 1,
            'extras' => $extras,
            'image' => base_url('images/kiosko/base_crust.png') // Usa la base como miniatura
        ];

        $cart[] = $customPizza;
        $session->set('cart', $cart);

        return redirect()->to('order')->with('success', '¡Tu pizza personalizada ha sido agregada!');
    }

    public function saveConfiguration()
    {
        if (!session()->has('user')) {
             return $this->response->setJSON(['status' => 'error', 'message' => 'Debes iniciar sesión para guardar.']);
        }

        $user = session()->get('user');
        $name = $this->request->getPost('name');
        $config = $this->request->getPost('configuration'); 

        if (empty($name) || empty($config)) {
             return $this->response->setJSON(['status' => 'error', 'message' => 'Datos incompletos.']);
        }

        $model = new \App\Models\SavedPizzaModel();
        $model->save([
            'user_id' => $user['id'],
            'name' => $name,
            'configuration' => $config
        ]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Pizza guardada en tus favoritas.']);
    }

    public function orderFavorite($id)
    {
        if (!session()->has('user')) {
            return redirect()->to('user/login');
        }

        $user = session()->get('user');
        $model = new \App\Models\SavedPizzaModel();
        
        $saved = $model->where('user_id', $user['id'])->where('id', $id)->first();
        
        if (!$saved) {
            return redirect()->back()->with('error', 'Pizza no encontrada.');
        }

        // Logica para construir el item del carrito desde la configuracion guardada
        $config = json_decode($saved['configuration'], true);
        
        // Carga la configuracion 
        session()->setFlashdata('loaded_pizza', $config);
        
        return redirect()->to('kiosko');
    }

    public function addFavoriteToCart($id)
    {
        if (!session()->has('user')) {
            return redirect()->to('user/login');
        }

        $user = session()->get('user');
        $model = new \App\Models\SavedPizzaModel();
        $saved = $model->where('user_id', $user['id'])->where('id', $id)->first();
        
        if (!$saved) {
             return redirect()->to('kiosko')->with('error', 'Pizza no encontrada.');
        }

        $config = json_decode($saved['configuration'], true);
        
        // RECALCULAR EL PRECIO

        // RECONSTRUCCION DE LA PIZZA
        $basePrice = 10.00;
        $price = $basePrice; 
        
        // Tamaño
        if(isset($config['size'])) {
           if($config['size'] == 'size_personal') $price = 5.00;
           if($config['size'] == 'size_medium') $price = 8.00;
           if($config['size'] == 'size_large') $price = 12.00;
        }
        
        //  precios de los ingredientes
        /* 
           Cheese: Mozz=0, Cheddar=2, Parm=2.5
           Toppings: Avg 1.5 (Jamon=1.5, Maiz=1, Pep=1.5, Cebolla=0.5, Champi=1.5, Pim=1, Aceit=1, Pina=1.5, Pollo=2, Tocin=2)
        */
        
        $extraDesc = [];
        
        if(isset($config['cheese'])) {
            if($config['cheese'] == 'cheese_cheddar') { $price += 2.00; $extraDesc[] = 'Queso Cheddar'; }
            elseif($config['cheese'] == 'cheese_parmesan') { $price += 2.50; $extraDesc[] = 'Queso Parmesano'; }
            else { $extraDesc[] = 'Mozzarella'; }
        }

        if(isset($config['toppings']) && is_array($config['toppings'])) {
             foreach($config['toppings'] as $t) {
                 // Aqui se calcula el precio de cada ingrediente
                 switch($t) {
                     case 'POLLO FRITO': $price += 2.00; $extraDesc[] = 'Pollo Frito'; break;
                     case 'TOCINETA': $price += 2.00; $extraDesc[] = 'Tocineta'; break;
                     case 'JAMON': 
                     case 'PEPERONI':
                     case 'CHAMPI':
                     case 'PIÑA': $price += 1.50; $extraDesc[] = ucfirst(strtolower($t)); break;
                     default: $price += 1.00; $extraDesc[] = ucfirst(strtolower($t)); break; // Default para verduras como maiz, pimientos
                 }
             }
        }

        // Agregar al carrito
        $session = session();
        $cart = $session->get('cart') ?? [];

        $customPizza = [
            'name' => $saved['name'], // Usar el nombre personalizado guardado
            'price' => (float) $price,
            'qty' => 1,
            'extras' => $extraDesc,
            'image' => base_url('images/kiosko/base_crust.png') 
        ];

        $cart[] = $customPizza;
        $session->set('cart', $cart);

        return redirect()->to('order')->with('success', '¡' . $saved['name'] . ' agregada al carrito!');
    }
}
