-- Vérifier les relations Louis Boudreau
SELECT 
    m.id as membre_id,
    m.nom,
    m.prenom,
    mc.id as membre_ceinture_id,
    c.nom as ceinture_nom,
    mc.date_obtention
FROM membres m
LEFT JOIN membre_ceintures mc ON m.id = mc.membre_id
LEFT JOIN ceintures c ON mc.ceinture_id = c.id
WHERE m.nom = 'Boudreau' AND m.prenom = 'Louis'
ORDER BY mc.date_obtention DESC;
