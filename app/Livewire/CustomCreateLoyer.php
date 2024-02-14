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
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;

use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

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

    public function render()
    {
        $this->remplir();
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
            ->schema([
                Group::make()
                ->schema([

                    Section::make()->schema([                    
                        TextInput::make('montant')
                        ->required()
                        ->numeric()
                        ->label('Montant ($)')
                        ->inlineLabel()
                        ->columnSpan(2)
                        ->default(0),
                        Hidden::make('nbr')
                        // ->label('Nonbre mois (Loyer anticipatif par mois)')
                        // ->options( ["2"=>2, "3"=>3,"4"=>4,"5"=>5,"6"=>6,"7"=>7,"8"=>8,"9"=>9,"10"=>10])
                        
                        
                        ->reactive(),
                        Toggle::make('garantie')
                        ->label('Utiliser la garantie'),
                    ])
                        ->columns(4),
                        Textarea::make('observation')
                            ->rows(5)
                            ->label('Observation'),
                            //->inlineLabel(),
                            //->columnSpan(4),
                        
                    ]),

            ])
            ->statePath('datas');
    }

    public function create()
    {
        $loc = Locataire::where('id',$this->locataire_id)->get();
        // dd($loc->value('mp'));

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
                        $nom = $this->locataire->noms;
                        $aff_mois = "";
                        foreach ($mois_dette as $v) {
                            $aff_mois .= "$v ,";
                        }
                        ///Modal::send();
                        //dd($aff_mois, $nom, $total);
                        /* Notification::make()
                        ->title('Saved successfully')
                        ->success()
                        ->send(); */
                        Notification::make()
                        ->title("Dettes trouvées")
                        ->body("$nom a un total des dettes de $total $, pour les mois de ($aff_mois)")
                        ->persistent()
                        ->danger()
                        ->send();
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
                    'garantie' => $this->form->getState()['garantie']
                ]);

                //dd($loyer_checking, 2);
        
                $this->form->fill();
                $this->dispatch('loyer-created');
                $this->remplir();
                return response()->streamDownload(function () use ($loyer) {
                    echo Pdf::loadHtml(
                        Blade::render('pdf', ['record' => $loyer])
                    )->stream();
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
                            'created_at' => $lelo
                        ];
                        $moiss[] = $Mois1[$nbr > 9 ? $nbr : '0'.$nbr];
                    }
                }
                
                // dd($reste, $nbr, $data);
                Loyer::insert($data);
                $records = Loyer::where(['locataire_id' => $this->locataire_id])
                    ->whereRaw("created_at = ?", [$lelo])
                    ->orderBy('created_at')
                    ->get();
                    //dd($records);
                $this->form->fill();
                $this->dispatch('loyer-created');
                $this->remplir();
                return response()->streamDownload(function () use ($records) {
                    echo Pdf::loadHtml(
                        Blade::render('anticipatif', ['records' => $records])
                    )->stream();
                }, 'loyerAnticipatif.pdf');
                //dd($mt, $reste);
            }
            else{
                dd('coucou');
                
                for ($i=$mois_en_numeric_start; $i < $this->form->getState()['nbr'] + $mois_en_numeric_start ; $i++) { 
                    # code...
                    $data[] =[
                        'montant' => $this->locataire->occupation->montant,
                        'mois' => $Mois1[$i > 9 ? $i : '0'.$i],
                        'annee' => $this->annee,
                        'locataire_id' => $this->locataire_id,
                        'observation' => $this->form->getState()['observation'],
                        'garantie' => $this->form->getState()['garantie'],
                        'created_at' => $lelo
                    ];
                    $moiss[] = $Mois1[$i > 9 ? $i : '0'.$i];
                }
                //dd($data);
                //$nbr = $this->form->getState()['montant'] / $this->locataire->occupation->montant;
                //$rest = $this->locataire->occupation->montant * 0.8571428571429;
                //dd('Toza na cas mususu', $nbr, $rest);
                //dd($moiss, $data);
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
            // dd($loyer_checking, 100);

            //dd("Loyer déjà payé pour ce mois-ci");
            Notification::make()
                ->title("Erreur")
                ->body("Loyer déjà payé pour ce mois-ci")
                ->warning()
                ->send();
            //return false;
            $this->form->fill();
            //$this->dispatch('loyer-created');
            $this->remplir();
            //echo "<script>alert('Loyer déjà payé pour ce mois-ci')</script>";
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
                        'garantie' => $this->form->getState()['garantie']
                    ]);
            
                    $this->form->fill();
                    //$this->dispatch('loyer-created');
                    $this->remplir();
                    return response()->streamDownload(function () use ($loyer) {
                        //dd($loyer, "sisi");
                        echo Pdf::loadHtml(
                            Blade::render('pdf', ['record' => $loyer])
                        )->stream();
                    }, '1.pdf');
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
                    'created_at' => $lelo
                ];
                $moiss = [];
                $moiss[] = $Mois1[$mois_en_numeric_start > 9 ? $mois_en_numeric_start : '0'.$mois_en_numeric_start];
    
                $nbr = intval(($this->form->getState()['montant'] - $mt_paye) / $this->locataire->occupation->montant);
                $reste = ($this->form->getState()['montant'] - $mt_paye) - ($this->locataire->occupation->montant * $nbr);
                //dd($nbr, $reste);
                $ctrA = 0;
                for ($i=$mois_en_numeric_start+1; $i < $nbr + $mois_en_numeric_start + 1 ; $i++) { 
                    # code...
                    if($i <= 12){
                        $data[] =[
                            'montant' => $this->locataire->occupation->montant,
                            'mois' => $Mois1[$i > 9 ? $i : '0'.$i],
                            'annee' => $this->annee,
                            'locataire_id' => $this->locataire_id,
                            'observation' => $this->form->getState()['observation'],
                            'garantie' => $this->form->getState()['garantie'],
                            'created_at' => $lelo
                        ];
                        $moiss[] = $Mois1[$i > 9 ? $i : '0'.$i];
                    }
                }
    
                if($reste > 0){
                    // dd($nbr);
                    if($nbr == 0){
                        $nbr = $mois_en_numeric_start + 1;
                    }
                    else {
                        $nbr +=2;
                    }
                    $nbr=$nbr > 12 ? 1: $nbr ;
                    if($data[count($data)-1]['mois'] == $Mois1[$nbr > 9 ? $nbr : '0'.$nbr] ) $nbr++;
                    // $this->annee=$nbr > 12 ? date('Y')+1: $this->annee ;
                    // dd($reste, $nbr,$data[count($data)-1]);

                    $data[] =[
                        'montant' => $reste,
                        'mois' => $Mois1[$nbr > 9 ? $nbr : '0'.$nbr],
                        'annee' => $nbr == 1 ? $this->annee+1: $this->annee,
                        'locataire_id' => $this->locataire_id,
                        'observation' => $this->form->getState()['observation'],
                        'garantie' => $this->form->getState()['garantie'],
                        'created_at' => $lelo
                    ];
                    $moiss[] = $Mois1[$nbr > 9 ? $nbr : '0'.$nbr];
                }
                // dd($moiss, $data);
                // dd($reste, $nbr, $data);
                Loyer::insert($data);
                $records = Loyer::whereIn('mois', $moiss)
                    //->where(['annee' => $this->annee, 'locataire_id' => $this->locataire_id])
                    ->whereRaw("created_at = ?  and locataire_id = $this->locataire_id", [$lelo])
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
        }

       
    }
    
    public function remplir(){
        //dd($this->locataire);
        $this->annee = $this->copy_annee;
        $this->data = Locataire::join('loyers', 'loyers.locataire_id', '=', 'locataires.id')
        ->selectRaw('locataires.*, loyers.montant, loyers.mois, loyers.annee, loyers.created_at as date_loyer, loyers.observation, loyers.garantie')
        ->where(['loyers.locataire_id' => $this->locataire_id, 'mois' => $this->mois, 'annee' => $this->annee])
        ->orderBy('locataires.id')
        ->get();

        //pdf
        $pdf = Pdf::loadHTML(Blade::render('situation', ['data' => $this->data, 'label' => 'SITUATION PERSONNELLE DU LOCATAIRE ', 'mois' => $this->mois, 'annee' => $this->annee, 'locataire' => $this->locataire]));
        //$pdf->save(public_path().'/pdf/doc.pdf');
        
        //$pdf = Pdf::loadHTML(Blade::render('evolution', ['data' => $this->data, 'label' => 'LOCATAIRE À JOUR DU MOIS DE '.$this->mois]));
        Storage::disk('public')->put('pdf/doc.pdf', $pdf->output());
    }

    public function imprimer($ly){
        dd($ly);
        
    }

    public function fermer(){
        
        $this->dispatch('close-modal', id: 'detail');
        //$this->dispatch('actualiser');
    }


    

    public function calculDettes($id){
        /*------------------------calcul des dettes------------------------------------*/
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
        $mois_dette = [];
        
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
                            $mois_dette[] = $locataire->loyers[$index-1]->mois;
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
                        $mois_dette[] = $loy->mois;
                    }
                    //echo "<script>alert($loy->mois)</script>";
                }
                else{
                    $total_mois += $loy->montant;
                }
        }
     
        return $total;
        /*-----------------------fin calcul des dettes---------------------------------*/
    }

    
}
