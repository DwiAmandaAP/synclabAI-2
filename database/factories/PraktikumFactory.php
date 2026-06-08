public function definition(): array
{
    return [
        'kode_praktikum' => $this->faker->unique()->bothify('LAB-####'),
        'nama_praktikum' => 'Praktikum ' . $this->faker->sentence(2),
        'angkatan'       => $this->faker->numberBetween(2021, 2025),
        'semester'       => $this->faker->numberBetween(1, 8),
    ];
}