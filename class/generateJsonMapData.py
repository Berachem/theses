"""
Fichier qui génère une partie du fichier json 
reliant un établissement avec son code région (à trois lettres)
qui servira pour la carte intéractive.
"""

import json

# Quelques data utiles pour la génération du json
villesRegions = {
 "Paris": "Île-de-France",
 "Marseille": "Provence-Alpes-Côte d'Azur",
 "Lyon": "Auvergne-Rhône-Alpes",
 "Toulouse": "Occitanie",
 "Nice": "Provence-Alpes-Côte d'Azur",
 "Nantes": "Pays de la Loire",
 "Strasbourg": "Grand Est",
 "Montpellier": "Occitanie",
 "Bordeaux": "Nouvelle-Aquitaine",
 "Lille": "Hauts-de-France",
 "Rennes": "Bretagne",
 "Reims": "Grand Est",
 "Le Havre": "Normandie",
 "Saint-Étienne": "Auvergne-Rhône-Alpes",
 "Toulon": "Provence-Alpes-Côte d'Azur",
 "Grenoble": "Auvergne-Rhône-Alpes",
 "Dijon": "Bourgogne-Franche-Comté",
 "Angers": "Pays de la Loire",
 "Villeurbanne": "Auvergne-Rhône-Alpes",
 "Le Mans": "Pays de la Loire",
 "Clermont-Ferrand": "Auvergne-Rhône-Alpes",
 "Aix-en-Provence": "Provence-Alpes-Côte d'Azur",
 "Brest": "Bretagne",
 "Limoges": "Nouvelle-Aquitaine",
 "Tours": "Centre-Val de Loire",
 "Amiens": "Hauts-de-France",
 "Perpignan": "Occitanie",
 "Besançon": "Bourgogne-Franche-Comté",
 "Orléans": "Centre-Val de Loire",
 "Metz": "Grand Est",
 "Rouen": "Normandie",
 "Nîmes": "Occitanie",
 "Mulhouse": "Grand Est",
 "Caen": "Normandie",
 "Nancy": "Grand Est",
 "Saint-Denis": "Île-de-France",
 "Argenteuil": "Île-de-France",
 "Montreuil": "Île-de-France",
 "Roubaix": "Hauts-de-France",
 "Tourcoing": "Hauts-de-France",
 "Avignon": "Provence-Alpes-Côte d'Azur",
 "Boulogne-Billancourt": "Île-de-France",
 "Saint-Paul": "La Réunion",
 "Poitiers": "Nouvelle-Aquitaine",
 "Versailles": "Île-de-France",
 "Colombes": "Île-de-France",
 "Aulnay-sous-Bois": "Île-de-France",
 "Rueil-Malmaison": "Île-de-France",
 "Antibes": "Provence-Alpes-Côte d'Azur",
 "Cannes": "Provence-Alpes-Côte d'Azur",
 "Saint-Denis": "La Réunion",
 "Saint-Pierre": "La Réunion",
 "La Rochelle": "Nouvelle-Aquitaine",
 "Antony": "Île-de-France",
 "Saint-Maur-des-Fossés": "Île-de-France",
 "Asnières-sur-Seine": "Île-de-France",
 "La Seyne-sur-Mer": "Provence-Alpes-Côte d'Azur",
 "Noisy-le-Grand": "Île-de-France",
 "Cergy": "Île-de-France",
 "Levallois-Perret": "Île-de-France",
 "Vitry-sur-Seine": "Île-de-France",
 "Évry": "Île-de-France",
 "Clichy": "Île-de-France",
 "Le Blanc-Mesnil": "Île-de-France",
 "Le Tampon": "La Réunion",
 "Pau": "Nouvelle-Aquitaine",
 "Calais": "Hauts-de-France",
 "Drancy": "Île-de-France",
 "Courbevoie": "Île-de-France",
 "Aubervilliers": "Île-de-France",
 "Champigny-sur-Marne": "Île-de-France",
 "Saint-Martin": "La Réunion"
}

regionCode = {
 "Île-de-France": "IDF",
 "Provence-Alpes-Côte d'Azur": "PACA",
 "Auvergne-Rhône-Alpes": "ARA",
 "Occitanie": "OCC",
 "Pays de la Loire": "PDL",
 "Grand Est": "GE",
 "Nouvelle-Aquitaine": "NA",
 "Hauts-de-France": "HDF",
 "Bretagne": "BRE",
 "Bourgogne-Franche-Comté": "BFC",
 "Centre-Val de Loire": "CVL",
 "Normandie": "NOR",
 "Guadeloupe": "GUA",
 "Martinique": "MAR",
 "Guyane": "GUY",
 "La Réunion": "REU",
 "Mayotte": "MAY",
 "Saint-Pierre-et-Miquelon": "SPM",
 "Wallis-et-Futuna": "WLF",
 "Polynésie française": "PYF",
 "Nouvelle-Calédonie": "NCL",
 "Île Saint-Paul et Ile Amsterdam": "SPM",
 "Montpellier": "Occitanie",
 "Saint-Étienne": "Auvergne-Rhône-Alpes",
 "Villeurbanne": "Auvergne-Rhône-Alpes",
 "Clermont-Ferrand": "Auvergne-Rhône-Alpes",
 "Aix-en-Provence": "Provence-Alpes-Côte d'Azur",
 "Limoges": "Nouvelle-Aquitaine",
 "Metz": "Grand Est",
 "Nancy": "Grand Est",
 "Paris": "Île-de-France",
 "Marseille": "Provence-Alpes-Côte d'Azur",
 "Lyon": "Auvergne-Rhône-Alpes",
 "Toulouse": "Occitanie",
 "Nice": "Provence-Alpes-Côte d'Azur",
 "Nantes": "Pays de la Loire",
 "Strasbourg": "Grand Est",
 "Bordeaux": "Nouvelle-Aquitaine",
 "Lille": "Hauts-de-France",
 "Rennes": "Bretagne",
 "Reims": "Grand Est",
 "Le Havre": "Normandie",
 "Toulon": "Provence-Alpes-Côte d'Azur",
 "Grenoble": "Auvergne-Rhône-Alpes",
 "Dijon": "Bourgogne-Franche-Comté",
 "Angers": "Pays de la Loire",
 "Le Mans": "Pays de la Loire",
 "Brest": "Bretagne",
 "Tours": "Centre-Val de Loire",
 "Amiens": "Hauts-de-France",
 "Perpignan": "Occitanie",
 "Besançon": "Bourgogne-Franche-Comté",
 "Orléans": "Centre-Val de Loire",
 "Rouen": "Normandie",
 "Nîmes": "Occitanie",
 "Mulhouse": "Grand Est",
 "Caen": "Normandie"
}




# generate json file from a previous one
def generateJsonMapData():
    # open json file
    with open('extract_theses.json', 'r',
              encoding="utf8") as json_file, open('etablissementRegions.json',
                                                  'w',
                                                  encoding="utf8") as f:
        # json to dict
        mapData = json.load(json_file)
        not_parcoured = list(set(map(lambda x : x['etablissements_soutenance'][0]["nom"], mapData)))
        for etablissement in mapData:
            
            for ville in villesRegions:
                # si l'établissement est dans la liste de ceux dont on a pas attribué de code région
                if etablissement['etablissements_soutenance'][0]["nom"] in not_parcoured:
                    # si le nom d'une ville est inclu dans le nom de l'établissement 
                    # ex : Paris 6 -> IDF
                    if ville.lower() in etablissement['etablissements_soutenance'][0]["nom"].lower():
                        print(etablissement['etablissements_soutenance'][0]["nom"].lower()+" ------> "+ville.upper())
                        f.write('"' + etablissement['etablissements_soutenance'][0]["nom"] +
                                '":"' + regionCode[villesRegions[ville]] + '",')
                        not_parcoured.remove(etablissement['etablissements_soutenance'][0]["nom"])
                        
                        

                        f.write('\n')
            
        f.write('\n')
        # on ajoute les établissements qui n'ont pas trouvé de code région
        for eta in set(not_parcoured):
            f.write('"' + eta + '":"' + 'NULL' + '",')
            f.write('\n')
        f.write('}')
        


#generateJsonMapData()
