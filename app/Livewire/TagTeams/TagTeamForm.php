<?php

declare(strict_types=1);

namespace App\Livewire\TagTeams;

use App\Livewire\Concerns\StandardForm;
use App\Models\TagTeam;
use App\Rules\WrestlerCanJoinNewTagTeam;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TagTeamForm extends Form
{
    use StandardForm;

    public ?TagTeam $formModel;

    protected string $formModelType = TagTeam::class;

    #[Validate(as: 'tag-teams.name')]
    public $name = '';

    #[Validate(as: 'employments.start_date')]
    public $start_date = '';

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3', Rule::unique('tag_teams', 'name')],
            'signature_move' => ['nullable', 'string', 'regex:/^[a-zA-Z\s\']+$/'],
            'start_date' => ['nullable', 'string', 'date'],
            'wrestlerA' => [
                'nullable',
                'bail',
                'integer',
                'different:wrestlerB',
                'required_with:start_date',
                'required_with:wrestlerB',
                'required_with:signature_move',
                Rule::exists('wrestlers', 'id'),
                new WrestlerCanJoinNewTagTeam,
            ],
            'wrestlerB' => [
                'nullable',
                'bail',
                'integer',
                'different:wrestlerA',
                'required_with:start_date',
                'required_with:wrestlerA',
                'required_with:signature_move',
                Rule::exists('wrestlers', 'id'),
                new WrestlerCanJoinNewTagTeam,
            ],
        ];
    }

    public function save()
    {
        $this->validate();

        $validated = $this->validate();

        try {
            if (isset($this->formModel)) {
                Gate::authorize('update', $this->formModel);
                $this->formModel->fill($validated);
            } else {
                Gate::authorize('create', TagTeam::class);
                $this->formModel = new TagTeam;
                $this->formModel->fill($validated);
            }

            return $this->formModel->save();
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }

        return false;
    }
}
