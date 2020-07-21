<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\Usuario;
use App\Models\Local;
use App\Models\LocalCaracteristica;
use App\Models\LocalEdificio;
use App\Models\LocalEquipamiento;
use App\Models\LocalMedia;
use App\Models\Sector;
use App\Models\Poblacion;

use App\Http\Controllers\ImageController;

use Illuminate\Support\Carbon;

use Faker\Factory as Faker;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $now = Carbon::now(new DateTimeZone('Europe/Madrid'));

        $user = Usuario::create([
			'nombre' => 'jose',
            'email' => 'j@j.com',
            'telefono' => '616666666',
            'pass' => 'pass',
            'rol' => 'administrador',
            'ultimo_login' => $now,
            'creado_en' => $now,
            'actualizado_en' => $now
        ]);

        $sectores = array();
        for($i = 0; $i < 15; $i++)
        {
            $sector = Sector::create([
                'titulo' => $faker->jobTitle,
                'descripcion' => $faker->sentence(100, true),
                'orden' => 1,
                'creado_en' => $now,
                'actualizado_en' => $now,
                'id_usuario_actualizacion' => $user->id
            ]);
            $sectores[] = $sector;
        }

        $poblaciones = array();
        for($i = 0; $i < 30; $i++)
        {
            $poblacion = Poblacion::create([
                'nombre' => $faker->country,
                'creado_en' => $now,
                'actualizado_en' => $now,
                'id_usuario_actualizacion' => $user->id
            ]);
            $poblaciones[] = $poblacion;
        }

        // Locales
        for($i = 0; $i < 200; $i++)
        {
            $titulo = $faker->streetAddress;
            $slug = Str::slug($titulo);

            $local = Local::create([
                'titulo' => $titulo,
                'url_amigable' => $slug,
                'telefono' => $faker->phoneNumber,
                'precio' => $faker->randomFloat(2, 5000, 40000),
                'metros' => $faker->randomNumber(3, false),
                'relevante' => 0,
                'extracto' => $faker->sentence(40, true),
                'descripcion' => $faker->sentence(300, true),
                'creado_en' => $now,
                'actualizado_en' => $now,
                'id_usuario_actualizacion' => $user->id,
                'id_sector' => $sectores[array_rand($sectores)]->id,
                'id_poblacion' => $poblaciones[array_rand($poblaciones)]->id
            ]);

            for($c = 0; $c < 5; $c++)
            {
                $caracteristica = LocalCaracteristica::create([
                    'id_local'=> $local->id,
                    'valor' => $faker->sentence(3, true),
                    'orden' => $c,
                    'creado_en' => $now,
                    'actualizado_en' => $now,
                    'id_usuario_actualizacion' => $user->id
                ]);
            }

            for($c = 0; $c < 2; $c++)
            {
                $edificio = LocalEdificio::create([
                    'id_local'=> $local->id,
                    'valor' => $faker->sentence(4, true),
                    'orden' => $c,
                    'creado_en' => $now,
                    'actualizado_en' => $now,
                    'id_usuario_actualizacion' => $user->id
                ]);

                $equipamiento = LocalEquipamiento::create([
                    'id_local'=> $local->id,
                    'valor' => $faker->sentence(4, true),
                    'orden' => $c,
                    'creado_en' => $now,
                    'actualizado_en' => $now,
                    'id_usuario_actualizacion' => $user->id
                ]);
            }
            $media = LocalMedia::create([
                'id_local'=> $local->id,
                'ruta' => 'storage/img/locales/principal/adfc6d21206c39ea7447712d9be0bb5f.jpg',
                'tipo' => 'principal',
                'orden' => 1,
                'creado_en' => $now,
                'actualizado_en' => $now,
                'id_usuario_actualizacion' => $user->id
            ]);
        }

    }
}
