SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:          Christophe Ferreira
-- Create date: 2019-07-08
-- Description:     Génération des étiquettes pour les 3 pays
-- =============================================

-- modiifer la procédure 
ALTER PROCEDURE spGenerationEtiquettes 
       
AS
BEGIN
        -- Suppression des données de la table sans effacer la table
       TRUNCATE TABLE [TraitementEttiquetesBEL]
       TRUNCATE TABLE [TraitementEttiquetesFR]
       TRUNCATE TABLE [TraitementEttiquetesCH]
        -- Déclaration des Variables
       DECLARE @QueryFR VARCHAR(8000), @QueryCH VARCHAR(8000), @QueryBEL VARCHAR(8000)
       DECLARE @QueryFRFinal VARCHAR(8000), @QueryCHFinal VARCHAR(8000), @QueryBELFinal VARCHAR(8000)
       DECLARE @Date VARCHAR(10)
       
       --SET DATE Ajout de la date d'aujourd'hui
       SET @Date = convert(VARCHAR, YEAR(GetDate())) + '-' + right('0' + convert(VARCHAR, MONTH(GetDate())), 2)  + '-' + right('0' + convert(VARCHAR, DAY(GetDate())), 2)


       --INSERT TABLE
       SET @QueryBELFinal = 'INSERT INTO [TraitementEttiquetesBEL] (PRENOM, NOM, ADRESSE1, CP, VILLE, TELFixe, TELPortable, EMAIL, INSTRUCTION_LIVRAISON, POIDS_COLIS) '
       SET @QueryFRFinal = 'INSERT INTO [TraitementEttiquetesFR] (PRENOM, NOM, ADRESSE1, CP, VILLE, TELFixe, TELPortable, EMAIL, INSTRUCTION_LIVRAISON, POIDS_COLIS) '
       SET @QueryCHFinal = 'INSERT INTO [TraitementEttiquetesCH] (comm_ShippingDate, clie_noclient, Civilite, Nom, Prenom, [Compl Adresse], Rue, NPA, [Localite], Email, TEL, TelMobile, [Note denvois]) '

       --CONTRUCT OPENQUERY MYSQL
       SET @QueryBEL = 'SELECT 
                                        PRENOM, NOM, adresse, npa, VILLE, 
                                        replace(replace(replace(telephone, ''+'', ''00''), ''0032'', ''0''), '' '', ''''), 
                                        replace(replace(replace(telephone2, ''+'', ''00''), ''0032'', ''0''), '' '', ''''),  
                                        CASE WHEN LEN(ISNULL(email, '''')) < 5 THEN ''suivi_poste@sekoya.ch'' else email end as email, 
                                        remarque, 
                                        CASE WHEN convert(VARCHAR, prix) = ''540.68'' THEN ''1171'' ELSE
                                               CASE WHEN convert(VARCHAR, prix) = ''249.30'' THEN ''1200''
                                        ELSE '''' END END As POIDS_COLIS
                                  FROM 
                                  OPENQUERY([AUBEP], ''SELECT 
                                                                          e.nom,
                                                                          e.nom2 as Prenom,
                                                                          e.npa,
                                                                          e.numero,
                                                                          e.adresse,
                                                                          e.telephone,
                                                                          e.telephone2,
                                                                          e.ville,
                                                                          v.dateDelai,
                                                                          v.Numero as NumeroComm,
                                                                          v.prix,
                                                                          v.nombrePlanPaiement as NBRBV,
                                                                          v.remarque,
                                                                          e.email,
                                                                          et.civilite,
                                                                          e.langueKey
                                                                   FROM 
                                                                          Vente v inner join
                                                                          Entite e on v.clientId = e.id inner join
                                                                          Entreprise et on e.id = et.id
                                                                   WHERE 
                                                                          v.CurrentState = ''''confirme'''' and
                                                                          v.dateDelai = ''''' + @Date + ''''' and 
                                                                          e.Pays = ''''BE'''''')'

       SET @QueryFR = 'SELECT 
                                        PRENOM, NOM, adresse, npa, VILLE, 
                                        replace(replace(replace(telephone, ''+'', ''00''), ''0033'', ''0''), '' '', ''''), 
                                        replace(replace(replace(telephone2, ''+'', ''00''), ''0033'', ''0''), '' '', ''''),  
                                        CASE WHEN LEN(ISNULL(email, '''')) < 5 THEN ''suivi_poste@sekoya.ch'' else email end as email, 
                                        remarque, 
                                        CASE WHEN convert(VARCHAR, prix) = ''540.68'' THEN ''1171'' ELSE
                                               CASE WHEN convert(VARCHAR, prix) = ''249.30'' THEN ''1200''
                                        ELSE '''' END END As POIDS_COLIS
                                  FROM 
                                  OPENQUERY([AUBEP], ''SELECT 
                                                                          e.nom,
                                                                          e.nom2 as Prenom,
                                                                          e.npa,
                                                                          e.numero,
                                                                          e.adresse,
                                                                          e.telephone,
                                                                          e.telephone2,
                                                                          e.ville,
                                                                          v.dateDelai,
                                                                          v.Numero as NumeroComm,
                                                                          v.prix,
                                                                          v.nombrePlanPaiement as NBRBV,
                                                                          v.remarque,
                                                                          e.email,
                                                                          et.civilite,
                                                                          e.langueKey
                                                                    FROM 
                                                                          Vente v inner join
                                                                          Entite e on v.clientId = e.id inner join
                                                                          Entreprise et on e.id = et.id
                                                                   WHERE 
                                                                          v.CurrentState = ''''confirme'''' and
                                                                          v.dateDelai = ''''' + @Date + ''''' and 
                                                                          e.Pays = ''''FR'''''')'


       --- FINAL SET BUILD
       SET @QueryFRFinal = @QueryFRFinal + ' ' + @QueryFR
       SET @QueryCHFinal = @QueryCHFinal + ' ' + @QueryCH
       SET @QueryBELFinal = @QueryBELFinal + ' ' + @QueryBEL

       --- FINAL EXEC FILL TABLES
       EXEC(@QueryFRFinal)
       EXEC(@QueryCHFinal)
       EXEC(@QueryBELFinal)


END
GO
