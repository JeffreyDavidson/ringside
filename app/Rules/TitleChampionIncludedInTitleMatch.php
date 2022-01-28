<?php

namespace App\Rules;

use App\Models\Title;
use Illuminate\Contracts\Validation\Rule;

class TitleChampionIncludedInTitleMatch implements Rule
{
    /**
     * @var array
     */
    protected $titleIds;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $titleIds)
    {
        $this->titleIds = $titleIds;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $titles = Title::findMany($this->titleIds);

        $titlesNotHavingChampionsInvoled = collect();

        foreach ($titles as $title) {
            if ($title->isVacant()) {
                break;
            }

            foreach ($value as $competitor) {
                dd($competitor);
            }
            if (! in_array($title->currentChampionship->champion->id, $value)) {
                $titlesNotHavingChampionsInvoled->push($title->id);
            }
        }

        return $titlesNotHavingChampionsInvoled->isEmpty();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This match requires the champion to be involved.';
    }
}
