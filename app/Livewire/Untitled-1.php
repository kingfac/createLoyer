
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
            //dd($total, $rapport);
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
                        ->body("Le montant à payer est supérieur à lalll garantie")
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
