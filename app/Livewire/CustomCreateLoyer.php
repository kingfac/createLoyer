<?php

namespace App\Livewire;

use DateTime;
use Exception;
use App\Models\Loyer;
use Livewire\Component;
use App\Models\Garantie;
use Filament\Forms\Form;
use App\Models\Locataire;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;

use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Support\Facades\Auth;

class CustomCreateLoyer extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $datas = [];

    public $locataire_id;
    public $data = [];
    public $locataire;
    public $annee;
    public $copy_annee;
    public $paie_loyer;
    public $mois;
    public $x = "";
    public $dettes_mois=[];
    public $dettes_annees = [];
    public $dettes_montant = [];
    public $dettes;
    public $ap;
    public $mp;

    public $Mois1 = [
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
    public $Mois2 = [
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
    public function render()
    {
        $this->remplir();
        $this->dettes = $this->calculDettesV();
        return view('livewire.custom-create-loyer');
    }

    public function mount(): void
    {
        $this->form->fill();
        $this->copy_annee = $this->annee;
        //dd($this->dt);
        $this->locataire = Locataire::find($this->locataire_id);
        $this->remplir();
    }

    public function form(Form $form): Form
    {
        $currentDate = new DateTime();
        return $form
            /* ->schema([
                Group::make()
                ->schema([

                    Section::make()->schema([                    
                        
                        Section::make()->schema([
                            TextInput::make('montant')
                            ->required()
                            ->numeric()
                            ->label('Montant ($)')
                            ->inlineLabel()
                            ->default(0),
                            Hidden::make('nbr')
                            // ->label('Nonbre mois (Loyer anticipatif par mois)')
                            // ->options( ["2"=>2, "3"=>3,"4"=>4,"5"=>5,"6"=>6,"7"=>7,"8"=>8,"9"=>9,"10"=>10])
                            
                            
                            ->reactive(),
                            Toggle::make('garantie')
                            ->label('Utiliser la garantie'),
                        ])->columns(1),

                        Textarea::make('observation')
                        ->label('Observation'),
                    ])
                        ->columns(12),
                       
                            //->inlineLabel(),
                            //->columnSpan(4),
                        
                    ]),

            ]) */
            ->schema([
                Section::make("Enregistrement du loyer (".$this->mois."  ".$this->annee.")")
                    ->schema([
                        Grid::make()->schema([
                            TextInput::make('montant')
                                ->required()
                                ->numeric()
                                ->label('Montant ($)')
                                ->inlineLabel()
                                ->default(0)
                                ->columnSpan(6),
                            Hidden::make('nbr')                           
                                ->reactive(),
                            Toggle::make('garantie')
                                ->label('Utiliser la garantie')
                                ->columnSpan(2),
                        ])
                        ->columns(12),
                        Textarea::make('observation')
                            ->label('Observation')
                            ->maxWidth("full"),
                        
                    ])
                    ->columnSpan(12)
                    ,
            ])
            ->statePath('datas');
    }

    public function create()
    {
        
        $loc = Locataire::where('id',$this->locataire_id)->get();
        $loys = Loyer::where('locataire_id', $this->locataire_id)->sum('montant');
        // dd($loys);
        // dd($loc->value('mp'), $loc->value('ap'), intval($this->Mois2[$this->mois]), intval($this->annee));
        
        $mois = intval($this->Mois2[$this->mois]);
        $mp = $loc->value('mp');
        $mm1=0;
        $m_dettes=[];
        // dd($mois, $mp);
        if($mois != $mp && $mois == 1){
            // dd('coucou');
            $mm1 = 12;

        }elseif($mois != $mp){
            $mm1 = intval(($mois-1));
        }else{
            $mm1 = $mois;
        }
        // dd($mm1);
        $ap = $loc->value('ap');
        $annee = intval($this->annee);
    
        $mv = 0;
        // dd($mm1);
        if($mm1 <= 9){
            $mv = $this->Mois1['0'.$mm1];

        }elseif($mm1 >= 10)
        {
            $mv = $this->Mois1[$mm1];
        }
        
        $loy_m1 = Loyer::where(['locataire_id' => $this->locataire_id, 'mois' => $mv])->sum('montant');
        // dd($loy_m1);
        
        if ($loc->value('mp') == null) {
            # $loc...
            return Notification::make()
                ->title('Erreur de paiement')
                ->body('Dans l\'enregistrement du locataire, vous n\'avez pas encore spécifié le premier mois de paiement du locataire.')
                ->success()
                ->icon('')
                ->iconColor('')
                ->duration(5000)
                ->persistent()
                ->actions([
                    
                ])
                ->send();
        }

        
        if (($mois != $mp && $ap == $annee && $loys == 0)  || ($mois < $mp && $ap == $annee && $loys > 0) ) {
            # $loc...
            return Notification::make()
                ->title('Erreur de paiement')
                ->body('Le mois de paiement est soit supérieur ou inférieur au premier mois de paiement.')
                ->success()
                ->icon('')
                ->iconColor('')
                ->duration(5000)
                ->persistent()
                ->actions([
                    
                    ])
                    ->send();
                }
                
                
        if($loy_m1 != null && $mois != $mp )
        {
            $mtp = $loye_occup = Loyer::where(['locataire_id' => $this->locataire_id, 'mois' => $mv])->get()[0]->locataire->occupation->montant;
           
            if($mtp == $loy_m1){
                ///il peut payer
            }elseif( $loy_m1 < $mtp){
                return Notification::make()
                ->title('Erreur de paiement')
                ->body("Impossible de payer ce mois car le locataire n'a payé que $loy_m1($)/$mtp($) au mois de $mv.")
                ->success()
                ->icon('')
                ->iconColor('')
                ->duration(5000)
                ->persistent()
                ->actions([
                    
                ])
                ->send();
            }
            
        }elseif($loy_m1 == null && $mois != $mp && $mp != null ){
            array_push($m_dettes, $mv);

            $test = Loyer::where(['locataire_id' => $this->locataire_id])->orderBy('id', 'DESC')->first();
            if($test != null){

                $dernier =intval($this->Mois2[ Loyer::where(['locataire_id' => $this->locataire_id])->orderBy('id', 'DESC')->first()->mois]);
                // dd($dernier);
                $new_mois = $mois-2;
    
                // dd($mois, $dernier);
                for ($i=$new_mois; $i > $dernier ; $i--) { 
                    if($i >= 10){
                        $m = $this->Mois1[$i];
                        array_push($m_dettes, $m);
                    }elseif($i <= 9){
                        $m = $this->Mois1['0'.$i];
                        array_push($m_dettes, $m);
                    }
                    # code...
                }
                // dd($m_dettes);
                $af_mois = "";
                foreach ($m_dettes as $v) {
                    $af_mois .= "$v ,";
                }
                return Notification::make()
                    ->title('Erreur de paiement')
                    ->body("Impossible de payer ce mois car le(s) mois de ($af_mois)  reste(ent) encore impayé(s).")
                    ->success()
                    ->icon('')
                    ->iconColor('')
                    ->duration(5000)
                    ->persistent()
                    ->actions([
                        
                    ])
                    ->send();
            }

            elseif ($test == null) {
                return Notification::make()
                    ->title('Erreur de paiement')
                    ->body("Impossible de payer ce mois.")
                    ->success()
                    ->icon('')
                    ->iconColor('')
                    ->duration(5000)
                    ->persistent()
                    ->actions([
                        
                    ])
                    ->send();
            }
        }


       

        
        
        //dd($this->form->getState());
        /* if ($this->form->getState()['garantie']) {
            dd(44);
            $montant = $this->form->getState()['montant'];
            //$locataire = Locataire::where('id',$this->form->getState()['locataire_id'])->first();
            $garantie = $this->locataire->garantie;

            //dd($montant,$this->locataire,$garantie);
            if(($garantie - $montant) < 0) {
                $this->dispatch('erreur-garantie');
            }          
            else{
                $this->locataire->update(['garantie' => $garantie-$montant]);
                //$this->form->fill();
                $this->dispatch('locataire-updated');
                return $this->store();
            }
        }
        else{
            
        } */
        

        $garantie = Garantie::where(['locataire_id'=> $this->locataire_id, 'restitution' => false])->sum('montant');
        $paie_garantie = Loyer::where(['locataire_id' => $this->locataire_id, 'garantie' => true])->sum('montant');
        $reste_garantie = $garantie - $paie_garantie;

        if($this->form->getState()['montant'] == 0 && $this->form->getState()['nbr'] == null){
            Notification::make()
            ->title("Erreur")
            ->body("Vous devez saisir un montant superieur à 0 ou precisser le nombre de mois pour un loyer anticipatif")
            ->warning()
            ->send();
        }
        else{
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
            $loyers = Loyer::where('locataire_id', $this->locataire_id)->orderByRaw('created_at')->get();
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
                        if(count($loyers) == 1 && $loy->montant != $this->locataire->occupation->montant){

                            $total += $this->locataire->occupation->montant - $total_mois;
                            $rapport[] = [$loy->mois ,$total_mois ,$this->locataire->occupation->montant, date("Y")-1];
                            $mois_dette[] = $loy->mois;
                        }
                        // dd($rapport, $total);
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
            // dd($total, $rapport, $total_mois, $nbrMois_paye, $this->mois);
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
            // dd($total, $rapport);
            if($total > 0){
                if(count(($rapport)) > 1){
                    if($rapport[0][0] == $this->mois && $rapport[0][3] == $this->annee){
                        return $this->store();
                    }
                    else{
                        if($mp == $mois && $annee == $ap){
                            return $this->store();
                        }else{

                            // $nom = $this->locataire->noms;
                            // $aff_mois = "";
                            // foreach ($mois_dette as $v) {
                            //     $aff_mois .= "$v ,";
                            // }
                            // ///Modal::send();
                            // //dd($aff_mois, $nom, $total);
                            // /* Notification::make()
                            // ->title('Saved successfully')
                            // ->success()
                            // ->send(); */
                            // Notification::make()
                            // ->title("Dettes trouvées")
                            // ->body("$nom a un total des dettes de $total $, pour les mois de ($aff_mois)")
                            // ->persistent()
                            // ->danger()
                            // ->send();

                            $this->store();
                        }
                    }
                }
                else{
                    
                    if($this->form->getState()['garantie'] == true && $this->form->getState()['montant'] < $reste_garantie  ){
                        
                        return $this->store();    
                    }elseif (!$this->form->getState()['garantie']) {
                        # code...
                        return $this->store();    
    
                    }else{
                        Notification::make()
                        ->title("Erreur de paiement")
                        ->body("Le montant à payer est supérieur à la garantie")
                        ->persistent()
                        ->danger()
                        ->duration(9000)
                        ->send();
                    }
                    
                }
                
            }
            else{
                
                if($this->form->getState()['garantie']  && $this->form->getState()['montant'] <= $reste_garantie  ){
                        
                    return $this->store();    
                }
                elseif (!$this->form->getState()['garantie']) {
                    # code...
                    return $this->store();    

                }
                else{
                    Notification::make()
                    ->title("Erreur de paiement")
                    ->body("Le montant à payer est supérieur à la garantie.")
                    ->persistent()
                    ->danger()
                    ->duration(9000)
                    ->send();
                }
            }
        } 
    }

    private function store(){
        
        
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
        $data = [];
        $mois_en_numeric_start = intval($Mois2[$this->mois]) ;
        $moiss = [];
        $loyer_checking = Loyer::where(['locataire_id' => $this->locataire_id, 'mois' => $this->mois, 'annee' => $this->annee])->sum('montant');
        
        $lelo = new DateTime('now');
        
        //s'il n'a pas encore déjà payé l'avance
        if($loyer_checking == 0){
            // dd($loyer_checking, 50);

            $ctrA = 0;
            if($this->form->getState()['montant'] <= $this->locataire->occupation->montant && $this->form->getState()['nbr'] == null){
                /* dd([
                    'montant' => $this->form->getState()['montant'],
                    'mois' => $this->mois,
                    'annee' => $this->annee,
                    'locataire_id' => $this->locataire_id,
                    'observation' => $this->form->getState()['observation'],
                    'garantie' => $this->form->getState()['garantie']
                ]); */
                $loyer = Loyer::create([
                    'montant' => $this->form->getState()['montant'],
                    'mois' => $this->mois,
                    'annee' => $this->annee,
                    'locataire_id' => $this->locataire_id,
                    'observation' => $this->form->getState()['observation'],
                    'garantie' => $this->form->getState()['garantie'],
                    'users_id' => Auth::user()->id
                ]);

                //dd($loyer_checking, 2);
        
                $this->form->fill();
                $this->dispatch('loyer-created');
                $this->remplir();
                return response()->streamDownload(function () use ($loyer) {
                    echo Pdf::loadHtml(
                        Blade::render('pdf', ['record' => $loyer])
                    )->setPaper('a4', 'portrait')->stream();
                }, $loyer->id.'1.pdf');
            }
            if($this->form->getState()['montant'] > $this->locataire->occupation->montant && $this->form->getState()['nbr'] == null){
                $nbr = intval($this->form->getState()['montant'] / $this->locataire->occupation->montant);
                $reste = $this->form->getState()['montant'] - ($this->locataire->occupation->montant * $nbr);
                $ctrA = 0;
                for ($i=$mois_en_numeric_start; $i < $nbr + $mois_en_numeric_start ; $i++) { 
                    # code...
                    if($i <= 12){

                        $data[] =[
                            'montant' => $this->locataire->occupation->montant,
                            'mois' => $Mois1[$i > 9 ? $i : '0'.$i],
                            'annee' => $this->annee,
                            'locataire_id' => $this->locataire_id,
                            'users_id' => Auth::user()->id,
                            'observation' => $this->form->getState()['observation'],
                            'garantie' => $this->form->getState()['garantie'],
                            'created_at' => $lelo
                        ];
                        $moiss[] = $Mois1[$i > 9 ? $i : '0'.$i];
                    }
                    else
                    {
                        $ctrA ++;
                        $data[] =[
                            'montant' => $this->locataire->occupation->montant,
                            'mois' => $Mois1[$ctrA > 9 ? $ctrA : '0'.$ctrA],
                            'annee' => $this->annee+1,
                            'locataire_id' => $this->locataire_id,
                            'observation' => $this->form->getState()['observation'],
                            'garantie' => $this->form->getState()['garantie'],
                            'users_id' => Auth::user()->id,
                            'created_at' => $lelo
                        ];
                        $moiss[] = $Mois1[$ctrA > 9 ? $ctrA : '0'.$ctrA];
                    }
                }
    
                if($reste > 0){
                    if($ctrA == 0){
                        $nbr +=$mois_en_numeric_start;
                        if($nbr == 13){
                            $nbr = 1;
                            $this->annee++;
                        }
                        $data[] =[
                            'montant' => $reste,
                            'mois' => $Mois1[$nbr > 9 ? $nbr : '0'.$nbr],
                            'annee' => $this->annee,
                            'locataire_id' => $this->locataire_id,
                            'observation' => $this->form->getState()['observation'],
                            'garantie' => $this->form->getState()['garantie'],
                            'users_id' => Auth::user()->id,
                            'created_at' => $lelo
                        ];
                        $moiss[] = $Mois1[$nbr > 9 ? $nbr : '0'.$nbr];
                    }
                    else{
                        $nbr = $ctrA + 1;
                        $data[] =[
                            'montant' => $reste,
                            'mois' => $Mois1[$nbr > 9 ? $nbr : '0'.$nbr],
                            'annee' => $this->annee + 1,
                            'locataire_id' => $this->locataire_id,
                            'observation' => $this->form->getState()['observation'],
                            'garantie' => $this->form->getState()['garantie'],
                            'users_id' => Auth::user()->id,
                            'created_at' => $lelo
                        ];
                        $moiss[] = $Mois1[$nbr > 9 ? $nbr : '0'.$nbr];
                    }
                }
                
                Loyer::insert($data);
                $records = Loyer::where(['locataire_id' => $this->locataire_id])
                    ->whereRaw("created_at = ?", [$lelo])
                    ->orderBy('created_at')
                    ->get();
                $this->form->fill();
                $this->dispatch('loyer-created');
                $this->remplir();
                return response()->streamDownload(function () use ($records) {
                    echo Pdf::loadHtml(
                        Blade::render('anticipatif', ['records' => $records])
                    )->stream();
                }, 'loyerAnticipatif.pdf');
            }
            else{
                
                for ($i=$mois_en_numeric_start; $i < $this->form->getState()['nbr'] + $mois_en_numeric_start ; $i++) { 
                    # code...
                    $data[] =[
                        'montant' => $this->locataire->occupation->montant,
                        'mois' => $Mois1[$i > 9 ? $i : '0'.$i],
                        'annee' => $this->annee,
                        'locataire_id' => $this->locataire_id,
                        'users_id' => Auth::user()->id,
                        'observation' => $this->form->getState()['observation'],
                        'garantie' => $this->form->getState()['garantie'],
                        'created_at' => $lelo
                    ];
                    $moiss[] = $Mois1[$i > 9 ? $i : '0'.$i];
                }
                Loyer::insert($data);
                $records = Loyer::whereIn('mois', $moiss)->where(['annee' => $this->annee, 'locataire_id' => $this->locataire_id])->get();
                $this->form->fill();
                $this->dispatch('loyer-created');
                $this->remplir();
                return response()->streamDownload(function () use ($records) {
                    echo Pdf::loadHtml(
                        Blade::render('anticipatif', ['records' => $records])
                    )->stream();
                }, 'loyerAnticipatif.pdf');
    
            }
        }
        else if($loyer_checking == $this->locataire->occupation->montant){

            Notification::make()
                ->title("Erreur")
                ->body("Loyer déjà payé pour ce mois-ci")
                ->warning()
                ->send();
            $this->form->fill();
            $this->remplir();
        }
        else{
            
            //si le montant est inferieur au loyer a payer et que ce meme montant est inferieur ou egal au reste a payer ce que ce n'est pas un payement anticipatif, juste le solde de la dette ou une avance du loyer
            if($this->form->getState()['montant'] <  $this->locataire->occupation->montant && $this->form->getState()['montant'] <= ($this->locataire->occupation->montant - $loyer_checking) ){
                try {
                    
                    //code...
                    $loyer = Loyer::create([
                        'montant' => $this->form->getState()['montant'],
                        'mois' => $this->mois,
                        'annee' => $this->annee,
                        'locataire_id' => $this->locataire_id,
                        'observation' => $this->form->getState()['observation'],
                        'garantie' => $this->form->getState()['garantie'],
                        'users_id' => Auth::user()->id
                    ]);
            
                    $this->form->fill();
                    //$this->dispatch('loyer-created');
                    $this->remplir();
                    $pdf = Pdf::loadHTML(Blade::render('pdf', ['record' => $loyer]))->setPaper('a5', 'portrait');
                    Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
                    return response()->download('../public/storage/pdf/doc.pdf');


                    // return response()->streamDownload(function () use ($loyer) {
                    //     //dd($loyer, "sisi");
                    //     echo Pdf::loadHtml(Blade::render('pdf', ['record' => $loyer]))->setPaper('a4', 'portrait')->stream();
                    // }, '1.pdf');
                    //landscape
                } catch (Exception $ex) {
                    //throw $th;
                    dd($ex->getMessage());
                }
            }
            else{

                $mt_paye = $this->locataire->occupation->montant - $loyer_checking;
                $data[] =[
                    'montant' => $mt_paye,
                    'mois' => $Mois1[$mois_en_numeric_start > 9 ? $mois_en_numeric_start : '0'.$mois_en_numeric_start],
                    'annee' => $this->annee,
                    'locataire_id' => $this->locataire_id,
                    'observation' => $this->form->getState()['observation'],
                    'garantie' => $this->form->getState()['garantie'],
                    'users_id' => Auth::user()->id,
                    'created_at' => $lelo
                ];
                $moiss = [];
                $moiss[] = $Mois1[$mois_en_numeric_start > 9 ? $mois_en_numeric_start : '0'.$mois_en_numeric_start];
    
                $nbr = intval(($this->form->getState()['montant'] - $mt_paye) / $this->locataire->occupation->montant);
                $reste = ($this->form->getState()['montant'] - $mt_paye) - ($this->locataire->occupation->montant * $nbr);
                $ctrA = 0;
                for ($i=$mois_en_numeric_start+1; $i < $nbr + $mois_en_numeric_start + 1 ; $i++) { 
                    if($i <= 12){
                        $data[] =[
                            'montant' => $this->locataire->occupation->montant,
                            'mois' => $Mois1[$i > 9 ? $i : '0'.$i],
                            'annee' => $this->annee,
                            'locataire_id' => $this->locataire_id,
                            'observation' => $this->form->getState()['observation'],
                            'garantie' => $this->form->getState()['garantie'],
                            'users_id' => Auth::user()->id,
                            'created_at' => $lelo
                        ];
                        $moiss[] = $Mois1[$i > 9 ? $i : '0'.$i];
                    }
                }
    
                if($reste > 0){
                    if($nbr == 0){
                        $nbr = $mois_en_numeric_start + 1;
                    }
                    else {
                        $nbr +=2;
                    }
                    $nbr=$nbr > 12 ? 1: $nbr ;
                    if($data[count($data)-1]['mois'] == $Mois1[$nbr > 9 ? $nbr : '0'.$nbr] ) $nbr++;

                    $data[] =[
                        'montant' => $reste,
                        'mois' => $Mois1[$nbr > 9 ? $nbr : '0'.$nbr],
                        'annee' => $nbr == 1 ? $this->annee+1: $this->annee,
                        'locataire_id' => $this->locataire_id,
                        'observation' => $this->form->getState()['observation'],
                        'garantie' => $this->form->getState()['garantie'],
                        'users_id' => Auth::user()->id,
                        'created_at' => $lelo
                    ];
                    $moiss[] = $Mois1[$nbr > 9 ? $nbr : '0'.$nbr];
                }
                Loyer::insert($data);
                $records = Loyer::whereIn('mois', $moiss)
                    ->whereRaw("created_at = ?  and locataire_id = $this->locataire_id", [$lelo])
                    ->get();
                $this->form->fill();
                $this->dispatch('loyer-created');
                $this->remplir();
                return response()->streamDownload(function () use ($records) {
                    echo Pdf::loadHtml(Blade::render('anticipatif', ['records' => $records]))->setPaper('a5', 'portrait')->stream();
                }, 'loyerAnticipatif.pdf');
            }
        }

       
    }
    
    public function remplir(){
        $this->annee = $this->copy_annee;
        $this->data = Locataire::join('loyers', '.locataire_id', '=', 'locataires.id')
        ->selectRaw('locataires.*, loyers.montant, loyers.mois, loyers.annee, loyers.created_at as date_loyer, loyers.observation, loyers.garantie,loyers.id as di, loyers.users_id')
        ->where(['loyers.locataire_id' => $this->locataire_id, 'mois' => $this->mois, 'annee' => $this->annee])
        ->orderBy('locataires.id')
        ->get();

        //pdf
        $pdf = Pdf::loadHTML(Blade::render('situation', ['data' => $this->data, 'label' => 'SITUATION PERSONNELLE DU LOCATAIRE ', 'mois' => $this->mois, 'annee' => $this->annee, 'locataire' => $this->locataire]))->setPaper('a4', 'portrait');
        //$pdf->save(public_path().'/pdf/doc.pdf');
        
        //$pdf = Pdf::loadHTML(Blade::render('evolution', ['data' => $this->data, 'label' => 'LOCATAIRE À JOUR DU MOIS DE '.$this->mois]));
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
    }


    public function imprimer($a,$m){
        // dd($m['mois']);
        $pdf = Pdf::loadHTML(Blade::render('pay_loy', ['record' => $a,'label' => 'Payement Loyer']))->setPaper('a5', 'portrait');
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
        return response()->download('../public/storage/pdf/doc.pdf');
        
    }


    public function fermer(){
        
        $this->dispatch('close-modal', id: 'detail');
        //$this->dispatch('actualiser');
    }



    public function calculDettesV(){
        /*------------------------calcul des dettes------------------------------------*/
     
        $annee_en_cours = intval(NOW()->format('Y'));
        $mois_dettes = [];
        $annee_dettes = [];
        $montant_dette = [];
        
        // $locataire = Locataire::where('id', $id)->first();
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
        
        $mois_en_cours = intval(NOW()->format('m'));

        //on recupere tous les locataires actifs
        $id = $this->locataire_id;

        $locataires = Locataire::where(['id' => $id,'actif' => true])->orderBy('id','DESC')->get();
        //on calcul des dettes pour chaque locataire
        $this->ap = $locataires->value('ap');
        $this->mp =  $locataires->value('mp');

        //on verifie d abord que le ap et mp existent
        if($locataires->value('ap') != null && $locataires->value('mp') != null){

            foreach ($locataires as $locataire) {
                
                //on recupere le mp et ap
                $mp_int =intval( $locataire->mp);
                $mp_trans = '';
                $ap_int = $locataire->ap;
                //-------------------------------
    
                //on transforme mp 02 => fevrier
                if($mp_int <= 9){
                    $mp_trans = $Mois1['0'.$mp_int];
                }
                elseif($mp_int >= 10){
                    $mp_trans = $Mois1[$mp_int];
                }
                //--------------------------------
            
                //on va parcourrir tous les mois a partir mp et ap jusque au mois en cours
    
                if($ap_int == $annee_en_cours){
                    
                    for ($mois=$mp_int; $mois <= $mois_en_cours ; $mois++) { 
    
                        $mois_n = '';
                        //on transforme mp 02 => fevrier
                        if($mois < 9){
                            $mois_n = $Mois1['0'.$mois];
                        }
                        elseif($mois >= 10){
                            $mois_n = $Mois1[$mois];
                        }
                        //--------------------------------
    
    
                        $loyer = Loyer::where('locataire_id', $locataire->id)->whereRaw(" (mois) = '$mois_n' and (annee) = '$annee_en_cours'  ")->get();
                        $loyer_montant = $loyer->sum('montant');
    
                        
                        if($loyer_montant < $locataire->occupation->montant )
                        {
                            
                            array_push($montant_dette, $loyer_montant);
                            array_push($mois_dettes, $mois_n);
                            array_push($annee_dettes, $ap_int);
    
                            
                            
                        }
                        
                       
                    }
                    
                    
                }
    
                if($ap_int < $annee_en_cours){
                    $mois_fin = 12;
                    $mp_com = $mp_int;
    
                    for ($ap_int; $ap_int <= $annee_en_cours  ; $ap_int ++) { 
    
                        
                        if($ap_int == $annee_en_cours)
                        {
                            $mois_fin = $mois_en_cours;
    
                        }
                        for ($mois=$mp_com; $mois <= $mois_fin ; $mois++) { 
        
                            $mois_n = '';
                            //on transforme mp 02 => fevrier
                            if($mois <= 9){
                                $mois_n = $Mois1['0'.$mois];
                            }
                            elseif($mois >= 10){
                                $mois_n = $Mois1[$mois];
                            }
                            //--------------------------------
        
        
                            $loyer = Loyer::where('locataire_id', $locataire->id)->whereRaw(" (mois) = '$mois_n' and (annee) = '$ap_int'  ")->get();
                            $loyer_montant = $loyer->sum('montant');
        
                            
                            if($loyer_montant < $locataire->occupation->montant )
                            {
                                // dd($locataire->occupation->montant,$loyer_montant);
                                
                                array_push($montant_dette, $loyer_montant);
                                array_push($mois_dettes, $mois_n);
                                array_push($annee_dettes, $ap_int);
                                
                                
                            }
                            
                             if($mois == 12){
                                 $mp_com = 1;
                             }
                            
                        }
    
                    }
    
                   
                    
                }            
                
                
                
                
            }
    
            ///on affecte les dettes 
            $this->dettes_mois = $mois_dettes;
            $this->dettes_annees = $annee_dettes;
            $this->dettes_montant = $montant_dette;
            // dd($mois_dettes, $montant_dette, array_sum($montant_dette), $annee_dettes);
            // return array_sum($montant_dette);


        }
    }


    

    public function calculDettes(){
        /*------------------------calcul des dettes------------------------------------*/
        $id = $this->locataire_id;

        $locataire = Locataire::where('id', $id)->first();
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
      
        $rapport = [];
        // $mois_dette = [];
        
        // $m est le mois parcouru enregistré pour le calcul de somme 
        $total = 0;
        $m = 0; // mois encour de traitement
        $total_mois = 0;
        $nbrMois_paye = 0;

        /* total loyer */
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
                        if ($total_mois < $locataire->occupation->montant) {
                            /* @endphp
                            <p>{{$loc->loyers[$loop->index-1]->mois}} : {{$total_mois}} / {{$loc->occupation->montant}}</p>
                            @php */
                            $total += $locataire->occupation->montant - $total_mois;
                            $rapport[] = [$locataire->loyers[$index-1]->mois ,$total_mois ,$locataire->occupation->montant, date("Y")-1];
                            $this->dettes_mois[] = $locataire->loyers[$index-1]->mois;
                        }
                    }
                    //chargement du mois suivant et calcul de la somme des loyers payess
                    $m = $mloyer;
                    $total_mois = 0;
                    $total_mois += $loy->montant;
                    $nbrMois_paye++;
                    
                    if(count($loyers) == 1){
                        $total += $locataire->occupation->montant - $total_mois;
                        $rapport[] = [$loy->mois ,$total_mois ,$locataire->occupation->montant, date("Y")-1];
                        $this->dettes_mois[] = $loy->mois;
                    }
                    //echo "<script>alert($loy->mois)</script>";
                }
                else{
                    $total_mois += $loy->montant;
                }
        }
        // dd($this->dettes_mois);
        // return $total;


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
                            $this->dettes_mois[] = $Mois1[$i > 9 ? $i : "0".$i];
                        }
                    }else{
                        /* Si tout se passe dans la meme annee */
                        for ($i = ($this->locataire->mp + $nbrMois_paye); $i <= $x_encour; $i++){
                            $total += $this->locataire->occupation->montant;
                            $rapport[] = [$Mois1[$i > 9 ? $i : "0".$i] ,0 ,$this->locataire->occupation->montant, date("Y")-1];
                            $this->dettes_mois[] = $Mois1[$i > 9 ? $i : "0".$i];
                        }
                    }
                }
                if ($Nba > 0){   
                    for ($i = 1; $i <= $mois_encours; $i++){
                        $total += $this->locataire->occupation->montant;
                        $rapport[] = [$Mois1[$i > 9 ? $i : "0".$i] ,0 ,$this->locataire->occupation->montant, date("Y")];
                        $this->dettes_mois[] = $Mois1[$i > 9 ? $i : "0".$i];
                    }
                }
            }
    }








        
        /*-----------------------fin calcul des dettes---------------------------------*/
    }

    
}
