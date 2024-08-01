--To set all form status to default
UPDATE permit_application
SET 
    surat_permohonan_status = 2,
	surat_indemnity_status = 2, 
    salinan_kad_pengenalan_status = 2,
    salinan_lesen_memandu_status = 2,
    salinan_geran_kenderaan_status = 2,
    salinan_insurans_kenderaan_status = 2,
    salinan_road_tax_status = 2,
    gambar_kenderaan_status = 2,
    surat_sokongan_status = 2;



--To reset permit (unapproved)
UPDATE permit_application
SET 
    `status` = 0,
    phc_check = 0,
    phc_approve = 0, 
    phc_second_approve = 0,
    jkr_check = 0,
    jkr_approve = 0,
    finance_check = 0,
    finance_approve = 0,
    transaction_status = 0
WHERE id = 101;

--To approve permit  (unapproved)
UPDATE permit_application
SET 
    `status` = 4,
    phc_check = 2,
    phc_approve = 2, 
    phc_second_approve = 2,
    jkr_check = 2,
    jkr_approve = 2,
    finance_check = 2,
    finance_approve = 2,
    transaction_status = 1
WHERE id = 101;



--Individual admin roles status
SELECT 
    id,
    `status`,
    phc_check,
    phc_approve, 
    phc_second_approve,
    jkr_check,
    jkr_approve,
    transaction_status,
    finance_check,
    finance_approve,
    surat_permohonan_status,
    surat_indemnity_status, 
    salinan_kad_pengenalan_status,
    salinan_lesen_memandu_status,
    salinan_geran_kenderaan_status,
    salinan_insurans_kenderaan_status,
    salinan_road_tax_status,
    gambar_kenderaan_status,
    surat_sokongan_status,
    customer_id,
    feedback_status,
    surat_permohonan,
    surat_indemnity, 
    salinan_kad_pengenalan,
    salinan_lesen_memandu,
    salinan_geran_kenderaan,
    salinan_insurans_kenderaan,
    salinan_road_tax,
    gambar_kenderaan,
    surat_sokongan,
    -- Comments columns at the end
    surat_permohonan_comment,
    surat_indemnity_comment,
    salinan_kad_pengenalan_comment,
    salinan_lesen_memandu_comment,
    salinan_geran_kenderaan_comment,
    salinan_insurans_kenderaan_comment,
    salinan_road_tax_comment,
    gambar_kenderaan_comment,
    surat_sokongan_comment
FROM 
    permit_application
WHERE 
    id IN (101, 123, 129);



--Customer complete transaction 
UPDATE 
    permit_application
SET 
    `transaction_status` = 1, 
    `status` = 3
WHERE 
    id = 139;

--Individual files status
SELECT 
    surat_permohonan_status,
	surat_indemnity_status, 
    salinan_kad_pengenalan_status,
    salinan_lesen_memandu_status,
    salinan_geran_kenderaan_status,
    salinan_insurans_kenderaan_status,
    salinan_road_tax_status,
    gambar_kenderaan_status,
    surat_sokongan_status
FROM 
    permit_application
WHERE 
    id = 1;