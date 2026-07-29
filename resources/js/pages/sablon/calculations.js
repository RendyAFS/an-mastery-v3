export function roundToHundreds(value) {
    return Math.round((Number(value) || 0) / 100) * 100;
}

export function formatNumber(val) {
    return new Intl.NumberFormat("id-ID").format(Number(val) || 0);
}

export function totalLongFabric(fabricRows) {
    return fabricRows.reduce(
        (sum, row) => sum + (Number(row.long_fabric) || 0),
        0,
    );
}

export function computeTotalSablon(totalLong, pricePerEmployee) {
    const price = Number(pricePerEmployee) || 0;
    if (!price) return 0;
    return totalLong * price;
}

export function computeRatePerLayer(totalSablon, colorCount) {
    const count = Number(colorCount) || 0;
    if (!count) return 0;
    return totalSablon / count;
}

export function computeFee(ratePerLayer, layers) {
    const fee = ratePerLayer * (Number(layers) || 0);

    return roundToHundreds(fee);
}
