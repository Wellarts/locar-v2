<?php

namespace App\Filament\Resources\LocacaoResource\Pages;

use App\Filament\Resources\ContasReceberResource;
use App\Filament\Resources\LocacaoResource;
use App\Models\Cliente;
use App\Models\ContasReceber;
use App\Models\FluxoCaixa;
use App\Models\Locacao;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;

class ManageLocacaos extends ManageRecords
{
    protected static string $resource = LocacaoResource::class;

    protected static ?string $title = 'Locações';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Novo')
                ->modalHeading('Criar Locação')
                ->after(
                    function ($data, $record) {
                       // dd([$record->id]);
                        if ($record->status_financeiro == true and $record->status_pago_financeiro == false) {
                            $valor_parcela = ($record->valor_total_financeiro / $record->parcelas_financeiro);
                            $vencimentos = Carbon::create($record->data_vencimento_financeiro);
                            for ($cont = 0; $cont < $data['parcelas_financeiro']; $cont++) {
                                $dataVencimentos = $vencimentos->addDays(7);
                                $parcelas = [
                                    'cliente_id' => $data['cliente_id'],
                                    'valor_total' => $data['valor_total_financeiro'],
                                    'parcelas' => $data['parcelas_financeiro'],
                                    'formaPgmto' => $data['formaPgmto_financeiro'],
                                    'ordem_parcela' => $cont + 1,
                                    'data_vencimento' => $dataVencimentos,
                                    'valor_recebido' => 0.00,
                                    'status' => 0,
                                    'obs' => 'Parcela referente a locação nº: '.$record->id.'',
                                    'valor_parcela' => $valor_parcela,
                                ];
                                ContasReceber::create($parcelas);
                            }
                        } elseif ($record->status_financeiro == true and $record->status_pago_financeiro == true) {

                            $parcelas = [
                                'cliente_id' => $data['cliente_id'],
                                'valor_total' => $data['valor_total_financeiro'],
                                'parcelas' => $data['parcelas_financeiro'],
                                'formaPgmto' => $data['formaPgmto_financeiro'],
                                'ordem_parcela' => 1,
                                'data_vencimento' => $data['data_vencimento_financeiro'],
                                'data_recebimento' => $data['data_vencimento_financeiro'],
                                'valor_recebido' => $data['valor_total_financeiro'],
                                'status' => 1,
                                'obs' => 'Recebimento referente da locação nº: '.$record->id.'',
                                'valor_parcela' => $data['valor_total_financeiro'],
                            ];
                            ContasReceber::create($parcelas);

                            $addFluxoCaixa = [
                                'valor' => $data['valor_total_financeiro'],
                                'tipo'  => 'CREDITO',
                                'obs'   => 'Recebimento referente da locação nº: '.$record->id.'',
                            ];

                            FluxoCaixa::create($addFluxoCaixa);
                        }
                    }
                ),













        ];
    }
}
