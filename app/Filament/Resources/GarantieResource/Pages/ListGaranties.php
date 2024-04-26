<?php

namespace App\Filament\Resources\GarantieResource\Pages;

use DateTime;
use App\Models\Loyer;
use Filament\Actions;
use App\Models\Garantie;
use App\Models\Locataire;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\GarantieResource;
use Illuminate\Support\Facades\Auth;

class ListGaranties extends ListRecords
{
    protected static string $resource = GarantieResource::class;
    public $locataire;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('Restituer garantie')
                ->form([
                    
                    Select::make('locataire_id')
                        ->relationship(
                            'locataire',
                            modifyQueryUsing: fn (Builder $query) => $query->where('actif', true)->where('garantie', '>' ,0),
                            )
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->noms} | {$record->occupation->typeOccu->nom} |{$record->num_occupation} ")
                        ->required()
                        ->validationMessages(['required' => 'Veuillez séléctionner un locataire']),

                ])
                ->action(function(array $data){
                    $paiements = Loyer::where('locataire_id', $data['locataire_id'])->where('garantie',true)->sum('montant');
                    $r_exist = Garantie::where(['locataire_id'=>$data['locataire_id'], 'restitution'=> true])->first();
                    if($r_exist == null){
                        
                        // dd($record->montant);
                        /*-----------------------calcul de la restitution------------------------------*/
                        $garanties = Garantie::where('locataire_id',$data['locataire_id'])->sum('montant');
                        
                        /*------------------------calcul des dettes------------------------------------*/
                        $this->locataire = Locataire::where('id', $data['locataire_id'])->first();
                        $Mois1 = [
                            '01' => 'Janvier',
                            '02' => 'Février',
                            '03' => 'Mars',
                            '04' => 'Avril',
                            '05' => 'Mais',
                            '06' => 'Juin',
                            '07' => 'Juillet',
                            '08' => 'Aout',
                            '09' => 'Septembre',
                            '10' => 'Octobre',
                            '11' => 'Novembre',
                            '12' => 'Décembre'
                        ];
                        $Mois2 = [
                            'Janvier' => '01',
                            'Février' => '02',
                            'Mars' => '03',
                            'Avril' => '04',
                            'Mais' => '05',
                            'Juin' => '06',
                            'Juillet' => '07',
                            'Aout' => '08',
                            'Septembre' => '09',
                            'Octobre' => '10',
                            'Novembre' => '11',
                            'Décembre' => '12'
                        ];
                        $_id = 0;
                        $ctrR = 0;
                        
                        $sommeGarentie = 0;
                        $sommeLoyerApay = 0;
                        $sommeLoyerPay = 0;
                        $voir = 0;
                        $rapport = [];
                        $mois_dette = [];
                        
                        // $m est le mois parcouru enregistré pour le calcul de somme 
                        $total = 0;
                        $m = 0; // mois encour de traitement
                        $total_mois = 0;
                        $somme_mois = [];
                        $nbrMois_paye = 0;

                        /* total loyer */
                        $totLoyer = 0;
                        $totDette =  0;
                        $ctr_check = 0; //s'il n'y a qu'un seul truc
                        $loyers = Loyer::where('locataire_id', $this->locataire->id)->orderByRaw('created_at')->get();
                        foreach ($loyers as $index => $loy)
                        {
                                //convertir mois en nombre
                                $mloyer = intval($Mois2[$loy->mois]);
                                //dd( $mloyer, $loy->mois);
                                //si ce n'est pas le meme mois qu'on traite
                                if($m != $mloyer){
                                    if($m != 0 ){
                                        //s'il a une dette par rapport a ce mois
                                        if ($total_mois < $this->locataire->occupation->montant) {
                                            /* @endphp
                                            <p>{{$loc->loyers[$loop->index-1]->mois}} : {{$total_mois}} / {{$loc->occupation->montant}}</p>
                                            @php */
                                            $total += $this->locataire->occupation->montant - $total_mois;
                                            $rapport[] = [$this->locataire->loyers[$index-1]->mois ,$total_mois ,$this->locataire->occupation->montant, date("Y")-1];
                                            $mois_dette[] = $this->locataire->loyers[$index-1]->mois;
                                        }
                                    }
                                    //chargement du mois suivant et calcul de la somme des loyers payess
                                    $m = $mloyer;
                                    $total_mois = 0;
                                    $total_mois += $loy->montant;
                                    $nbrMois_paye++;
                                    
                                    if(count($loyers) == 1){
                                        $total += $this->locataire->occupation->montant - $total_mois;
                                        $rapport[] = [$loy->mois ,$total_mois ,$this->locataire->occupation->montant, date("Y")-1];
                                        $mois_dette[] = $loy->mois;
                                    }
                                    //echo "<script>alert($loy->mois)</script>";
                                }
                                else{
                                    $total_mois += $loy->montant;
                                }
                        }
                        /* if(count($rapport) == 0 && $total_mois > 0){
                            $rapport[] = [$this->mois ,$total_mois ,$this->locataire->occupation->montant, date("Y")-1];
                        } */
                        
                        //dd($total_mois);
                        //dd($total, $rapport, $total_mois, $nbrMois_paye, $this->mois);
                        /* Affichage des arrieres s'il y a */
                            $Nba = date("Y") - $this->locataire->ap; //nombre d'annee
                            $mois_encours = date("m"); //mois encours
                            $nbMois = ((13 * $Nba) - $this->locataire->mp) + date("m"); //nombre de mois total
                            $x_encour = ($Nba == 0) ? $mois_encours :  (13 - $this->locataire->mp - $nbrMois_paye); // nombre de mois de l'annee precedente s'il y a 
                        
                        

                        /* Affichage de mois d'arrieressss */
                        if ($this->locataire->ap != null)
                        {                                                       
                                if ($x_encour >= 0){
                                    if ($x_encour > 0){    
                                        if ($Nba != 0){
                                            for ($i = ($this->locataire->mp + $nbrMois_paye); $i <= 12; $i++){
                                                $total += $this->locataire->occupation->montant;
                                                $rapport[] = [$Mois1[$i > 9 ? $i : "0".$i] ,0 ,$this->locataire->occupation->montant, date("Y")-1];
                                                $mois_dette[] = $Mois1[$i > 9 ? $i : "0".$i];
                                            }
                                        }else{
                                            /* Si tout se passe dans la meme annee */
                                            for ($i = ($this->locataire->mp + $nbrMois_paye); $i <= $x_encour; $i++){
                                                $total += $this->locataire->occupation->montant;
                                                $rapport[] = [$Mois1[$i > 9 ? $i : "0".$i] ,0 ,$this->locataire->occupation->montant, date("Y")-1];
                                                $mois_dette[] = $Mois1[$i > 9 ? $i : "0".$i];
                                            }
                                        }
                                    }
                                    if ($Nba > 0){   
                                        for ($i = 1; $i <= $mois_encours; $i++){
                                            $total += $this->locataire->occupation->montant;
                                            $rapport[] = [$Mois1[$i > 9 ? $i : "0".$i] ,0 ,$this->locataire->occupation->montant, date("Y")];
                                            $mois_dette[] = $Mois1[$i > 9 ? $i : "0".$i];
                                        }
                                    }
                                }
                        }
                       
                        /*-----------------------fin calcul des dettes---------------------------------*/
                        
            
                        $restitution = $garanties-$paiements-$total;
    
                        $restitution = Garantie::create([
                            'montant' => $restitution,
                            'locataire_id' => $data['locataire_id'],
                            'users_id' => Auth::user()->id,
                            'restitution' => true,
                        ]);
                        $g = Garantie::where('locataire_id',$data['locataire_id'])->orderBy('restitution')->get();
                        Locataire::find($data['locataire_id'])->update(["actif" => false]);
                        return response()->streamDownload(function () use ($g, $paiements) {
                            echo Pdf::loadHtml(
                                Blade::render('restitution', ['data' => $g, 'loyers'=> $paiements])
                            )->stream();
                        }, '1.pdf'); 
    
                    }
                    else{
                        Notification::make()
                        ->title('Erreur de restitution')
                            ->body('Ce locataire a déjà été restitué.')
                            ->danger()
                            ->icon('')
                            ->iconColor('')
                            ->duration(5000)
                            ->persistent()
                            ->actions([
                                
                                ])
                                ->send();
                    }
             
                }),
                Action::make('imprimer')
                    ->button()
                    ->icon('heroicon-m-printer')
                    ->form([
                        Select::make('locataire_id')
                            ->label('Locataire')
                            ->options(Locataire::query()->pluck('noms', 'id'))
                            ->searchable()
                            ->required()
                    ])
                    ->action(function(array $data){
                        $lelo = new DateTime('now');
                        $lelo = $lelo->format('d-m-Y');

                        $loc = Locataire::find($data["locataire_id"]);

                        $pdf = Pdf::loadHTML(Blade::render('locataire_gar', ['loc' => $loc, 'label' => 'Total Garentie']));
                        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
                        return response()->download('../public/storage/pdf/doc.pdf');
                    })
        ];
    }
}
