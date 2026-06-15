<?php

namespace Tests\Browser;

use App\Models\Item;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PurchasePaymentTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_payment_method_is_reflected_on_purchase_page()
    {
        $user = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'email_verified_at' => now(),
        ]);

        $item = Item::factory()->create([
            'name' => 'Duskテスト商品',
            'price' => 10000,
        ]);

        $this->browse(function (Browser $browser) use ($user, $item) {
            $browser->loginAs($user)
                ->visit('/purchase/' . $item->id)
                ->assertSee('支払い方法')
                ->assertSee('選択してください')
                ->click('.custom-select__selected')
                ->click('.custom-select__option[data-value="コンビニ支払い"]')
                ->assertSeeIn('#selected-payment', 'コンビニ支払い')
                ->assertInputValue('#payment_method', 'コンビニ支払い');
        });
    }
    public function test_card_payment_method_is_reflected_on_purchase_page()
{
    $user = User::factory()->create([
        'postal_code' => '123-4567',
        'address' => '東京都渋谷区',
        'building' => 'テストビル101',
        'email_verified_at' => now(),
    ]);

    $item = Item::factory()->create([
        'name' => 'Duskカード商品',
        'price' => 10000,
    ]);

    $this->browse(function (Browser $browser) use ($user, $item) {
        $browser->loginAs($user)
            ->visit('/purchase/' . $item->id)
            ->click('.custom-select__selected')
            ->click('.custom-select__option[data-value="カード支払い"]')
            ->assertSeeIn('#selected-payment', 'カード支払い')
            ->assertInputValue('#payment_method', 'カード支払い');
    });
}
}