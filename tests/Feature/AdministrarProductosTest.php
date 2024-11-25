<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Tests\TestCase;

class AdministrarProductosTest extends TestCase
{
    use RefreshDatabase;
    
    //Test para verificar que el usuario sea redirigido al login si no hay sesion iniciada.
    public function test_usuario_es_redirigido_al_login_si_no_esta_autenticado()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login')); //Debe redirigir a la página de login.
    }

    //Test para verificar que el usuario tenga acceso al dashboard si ha sido autenticado.
    public function test_usuario_tiene_acceso_al_dashboard_si_esta_autenticado()
    {
        $user = User::factory()->create(); //Crear un usuario.
        $this->actingAs($user); //Iniciar sesión como este usuario.
        
        //Verificar que carga correctamente.
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
    }

    //Test para verificar que el usuario al estar en el dashboard es capaz de redirigirse a la sección de administrar productos.
    public function test_usuario_administrador_se_redirige_a_la_seccion_de_administrar_productos_desde_la_barra_de_navegacion()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.
        
        //Verificar que carga correctamente.
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        //Verificar que el enlace de "Administrar Productos" está presente.
        $response->assertSee('Administrar Productos');
        $response->assertSee(route('editProduct'));

        //Simular el clic en el enlace de "Administrar Productos".
        $response = $this->get(route('editProduct'));
        $response->assertStatus(200);

        //Verificar que se redirige correctamente a la página de "Administrar Productos"
        $response->assertSee('Administrar Productos');
    }

    //Test para verificar que los produtos existentes se cargan correctamente en la sección de productos.
    public function test_productos_existentes_se_cargan_correctamente_en_la_seccion_de_productos()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Crear algunos productos utilizando el ProductFactory.
        $products = Product::factory()->count(5)->create();

        //Acceder a la página de administrar productos.
        $response = $this->get(route('editProduct'));
        $response->assertStatus(200);

        //Verificar que cada producto esté presente en la vista con su respectiva información.
        foreach ($products as $product) {
            $response->assertSee($product->name);
            $response->assertSee($product->description);
            $response->assertSee($product->category);
            $response->assertSee((string)$product->price); //Convertir a string.
            $response->assertSee((string)$product->stock); //Convertir a string.
            $response->assertSee(asset('images/' . $product->image)); //Verificar la imagen del producto.
        }
    }

    //Test para verificar que el filtro de búsqueda funciona correctamente.
    public function test_filtro_de_busqueda_funciona_correctamente()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Crear productos con datos variados.
        $products = [
            ['name' => 'Producto A', 'stock' => 50, 'price' => 10, 'category' => 'Electrónica'],
            ['name' => 'Producto B', 'stock' => 20, 'price' => 5, 'category' => 'Hogar'],
            ['name' => 'Producto C', 'stock' => 10, 'price' => 20, 'category' => 'Juguetes'],
            ['name' => 'Producto D', 'stock' => 30, 'price' => 15, 'category' => 'Hogar'],
            ['name' => 'Producto E', 'stock' => 40, 'price' => 25, 'category' => 'Electrónica'],
        ];
        
        foreach ($products as $product) {
            Product::factory()->create($product);
        }

        //Acceder a la página de administrar productos.
        $response = $this->get(route('editProduct'));
        $response->assertStatus(200);

        //Simular los filtros y verificar el orden de los productos (Colocamos según deberían aparecer, ya que luego se comparará con el resultado real).
        $filters = [
            'a-z' => ['Producto A', 'Producto B', 'Producto C', 'Producto D', 'Producto E'],
            'z-a' => ['Producto E', 'Producto D', 'Producto C', 'Producto B', 'Producto A'],
            'stock-high-low' => ['Producto A', 'Producto E', 'Producto D', 'Producto B', 'Producto C'],
            'stock-low-high' => ['Producto C', 'Producto B', 'Producto D', 'Producto E', 'Producto A'],
            'price-low-high' => ['Producto B', 'Producto A', 'Producto D', 'Producto C', 'Producto E'],
            'price-high-low' => ['Producto E', 'Producto C', 'Producto D', 'Producto A', 'Producto B'],
            'category' => ['Electrónica', 'Electrónica', 'Hogar', 'Hogar', 'Juguetes'],
        ];

        //Recorremos cada filtro, comparamos con los datos reales y los esperados para ver si coinciden.
        foreach ($filters as $filter => $expectedOrder) {
            //Realizar una solicitud GET simulando el filtro seleccionado.
            $response = $this->get(route('editProduct', ['filter' => $filter]));
            $response->assertStatus(200);
    
            //Verificar que los productos estén en el orden esperado.
            foreach ($expectedOrder as $index => $productName) {
                $response->assertSeeInOrder([$productName]);
            }
        }
    }

    //Test para verificar que la barra de búsqueda funciona correctamente.
    public function test_barra_de_busqueda_funciona_correctamente()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Crear productos de prueba.
        $products = [
            ['name' => 'Producto A', 'stock' => 50, 'price' => 10, 'category' => 'Electrónica'],
            ['name' => 'Producto B', 'stock' => 20, 'price' => 5, 'category' => 'Hogar'],
            ['name' => 'Producto C', 'stock' => 10, 'price' => 20, 'category' => 'Juguetes'],
            ['name' => 'Producto D', 'stock' => 30, 'price' => 15, 'category' => 'Hogar'],
            ['name' => 'Producto E', 'stock' => 40, 'price' => 25, 'category' => 'Electrónica'],
        ];

        foreach ($products as $product) {
            Product::factory()->create($product);
        }

        //Acceder a la página de administrar productos.
        $response = $this->get(route('editProduct'));
        $response->assertStatus(200);

        //Simular una búsqueda.
        $response->assertSee('Buscar Producto'); //Asegurarse de que la barra de búsqueda esté visible.

        //Buscar un producto específico.
        $searchTerm = 'Producto A';
        $response = $this->get(route('editProduct', ['search' => $searchTerm]));

        //Verificar que el producto buscado esté presente en la respuesta.
        $response->assertSee($searchTerm);

        //Verificar que los productos que no coinciden con la búsqueda no estén presentes.
        $response->assertDontSee('Producto B');
        $response->assertDontSee('Producto C');
        $response->assertDontSee('Producto D');
        $response->assertDontSee('Producto E');
    }

    //Test para verificar que se puede eliminar un producto de la vista.
    public function test_eliminar_producto_funciona_correctamente()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Crear un producto de prueba.
        $product = Product::factory()->create([
            'name' => 'Producto de prueba',
            'stock' => 10,
            'price' => 15,
            'category' => 'Electrónica',
        ]);

        //Asegurarse de que el producto existe en la base de datos.
        $this->assertDatabaseHas('products', ['id' => $product->id]);

        //Acceder a la página de administrar productos.
        $response = $this->get(route('editProduct'));
        $response->assertStatus(200);

        //Realizar una solicitud DELETE para eliminar el producto.
        $response = $this->delete(route('deleteProduct', $product->id));

        //Verificar que el producto ha sido eliminado de la base de datos.
        $this->assertDatabaseMissing('products', ['id' => $product->id]);

        //Verificar que la respuesta redirige correctamente
        $response->assertStatus(302);

        //Verificar que un mensaje de éxito esté presente en la sesión
        $response->assertSessionHas('success', 'Producto eliminado correctamente.');
    }

    //Test para verificar que se puede editar un producto de la vista.
    public function test_editar_producto_funciona_correctamente()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Crear categorías de prueba.
        $category = Category::factory()->create();

        //Crear un producto de prueba.
        $product = Product::factory()->create([
            'category' => $category->id,
        ]);

        //Asegurarse de que el producto existe en la base de datos.
        $this->assertDatabaseHas('products', ['id' => $product->id]);

        //Acceder a la página de administrar productos.
        $response = $this->get(route('editProduct'));
        $response->assertStatus(200);

        $response->assertSee(route('editOneProduct', $product->id)); //Verificar que el enlace de edición existe en la vista.
        
        $response = $this->get(route('editOneProduct', $product->id)); //Simular una solicitud a la vista de edición.
        
        $response->assertStatus(200); //Asegurarse de que la vista se carga correctamente.
        $response->assertViewIs('editOneProduct'); //Confirmar que la vista cargada es la correcta.
        $response->assertSee($product->name); //Verificar que los datos del producto están presentes.
    
        $updatedData = [
            'name' => 'Producto Editado',
            'price' => 123.45,
            'stock' => 20,
            'description' => 'Descripción editada.',
            'category' => $category->id,
        ];

        $response = $this->put(route('updateProduct', $product->id), $updatedData); //Simular la actualización.
        
        //Acceder a la página de administrar productos.
        $response->assertRedirect(route('editProduct', ['id' => $product->id]));

        //Verificar que un mensaje de éxito esté presente en la sesión.
        $response->assertSessionHas('success', 'Producto actualizado con éxito.');

        //Verificar que los cambios se reflejan en la base de datos.
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $updatedData['name'],
            'price' => $updatedData['price'],
            'stock' => $updatedData['stock'],
            'description' => $updatedData['description'],
            'category' => $category->name,
        ]);
    }

    //Test para verificar que no se envia el formulario para agregar un nuevo producto.
    public function test_no_se_envia__el_formulario_para_agregar_producto_si_los_campos_estan_vacios()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Acceder a la página de administrar productos.
        $response = $this->get(route('editProduct'));
        $response->assertStatus(200);

        //Simular la solicitud POST con todos los campos vacíos.
        $response = $this->post(route('storeProduct'), []);

        //Verificar que el formulario no se envía y se redirige de vuelta con errores.
        $response->assertSessionHasErrors(['name', 'price', 'stock', 'category']);
        $response->assertRedirect();
    }

    //Test para verificar que se agrega un nuevo producto correctamente.
    public function test_agregar_un_nuevo_producto_correctamente()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.

        Storage::fake('images');

        //Crear una categoría de prueba.
        $category = Category::factory()->create([
            'name' => 'Prueba',
        ]);

        //Crear una imagen simulada
        $fakeImage = UploadedFile::fake()->image('producto.jpg');

        //Datos del producto a agregar.
        $productData = [
            'name' => 'Producto de Prueba',
            'price' => 50.00,
            'stock' => 10,
            'category' => $category->name,
            'description' => 'Descripción de prueba.',
            'image' => UploadedFile::fake()->image('producto.jpg'),
        ];

        //Acceder a la página de administrar productos.
        $response = $this->get(route('editProduct'));
        $response->assertStatus(200);

        //Simular la solicitud POST para agregar el producto.
        $response = $this->post(route('storeProduct'), $productData);

        //Verificar redireccionamiento.
        $response->assertRedirect(route('editProduct'));

        //Verificar que un mensaje de éxito esté presente en la sesión.
        $response->assertSessionHas('success', 'Producto agregado exitosamente.');

        //Verificar que el producto se agregó a la base de datos comparando algunos datos.
        $this->assertDatabaseHas('products', [
            'name' => $productData['name'],
            'price' => $productData['price'],
            'stock' => $productData['stock'],
            'description' => $productData['description'],
        ]);
    }

    //Test para verificar que el usuario puede cerrar la sesión desde la vista de administrar productos.
    public function test_usuario_puede_cerrar_sesion_desde_la_vista_de_administrar_productos()
    {
        //Crear un usuario.
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user); //Iniciar sesión como este usuario.

        //Acceder a la página de administrar productos.
        $response = $this->get(route('editProduct'));

        //Verificar que carga correctamente.
        $response->assertStatus(200);

        //Verificar que el nombre del usuario aparece en el botón.
        $response->assertSee($user->name);

        //Simular un clic en el botón del usuario (mostrar el dropdown).
        $response = $this->get(route('editProduct'), [
            'headers' => [
                'X-Requested-With' => 'XMLHttpRequest', //Necesario para solicitudes AJAX.
            ]
        ]);

        //Verificar que la opción de "Cerrar Sesión" está presente.
        $response->assertSee('Cerrar Sesión');

        //Simular el clic en "Cerrar Sesión".
        $response = $this->post(route('logout'));

        //Verificar que el usuario fue redirigido a la página de inicio de sesión.
        $response->assertRedirect('/');
        $this->assertGuest(); //Verificar que el usuario ya no está autenticado.
    }
}