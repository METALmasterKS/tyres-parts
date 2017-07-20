-- запрос чтоб выбрать только шины по евро прайсам

SELECT tb.name, tm.name, tm.season, t.width, t.height, t.diameter, t.load, t.speed, t.spikes, t.XL, t.RFT, tp.tyreId, GROUP_CONCAT(tp.providerId) as prov 
FROM `tyres_prices` tp 
LEFT JOIN tyres t ON t.id = tp.tyreId
LEFT JOIN tyres_models tm ON tm.id = t.modelId
LEFT JOIN tyres_brands tb ON tb.id = tm.brandId
GROUP by tyreId 
HAVING prov LIKE '7'