export function roundToHundreds(value) {
    return Math.round((Number(value) || 0) / 100) * 100;
}

export function formatNumber(val) {
    return new Intl.NumberFormat("id-ID").format(Number(val) || 0);
}

export function formatSignedNumber(val) {
    const number = Number(val) || 0;

    if (number < 0) {
        return "-" + new Intl.NumberFormat("id-ID").format(Math.abs(number));
    }

    return new Intl.NumberFormat("id-ID").format(number);
}

export function parseSignedNumber(value) {
    if (!value) return 0;

    const isNegative = value.trim().startsWith("-");
    const clean = value.replace(/[^\d]/g, "");

    return isNegative ? -(Number(clean) || 0) : Number(clean) || 0;
}

/**
 * Sum of `long_fabric` across all fabric rows.
 */
export function totalLongFabric(fabricRows) {
    return fabricRows.reduce(
        (sum, row) => sum + (Number(row.long_fabric) || 0),
        0,
    );
}

/**
 * Total Sablon = Total Long Fabric x Price per Employee.
 * Catatan: total sablon TIDAK dibagi jumlah warna.
 */
export function computeTotalSablon(totalLong, pricePerEmployee) {
    const price = Number(pricePerEmployee) || 0;
    if (!price) return 0;
    return totalLong * price;
}

/**
 * Rate per layer = Total Sablon / jumlah warna (type color count).
 */
export function computeRatePerLayer(totalSablon, colorCount) {
    const count = Number(colorCount) || 0;
    if (!count) return 0;
    return totalSablon / count;
}

/**
 * Fee per employee row = rate per layer x jumlah layers di row tersebut.
 */
export function computeFee(ratePerLayer, layers) {
    const fee = ratePerLayer * (Number(layers) || 0);

    return roundToHundreds(fee);
}

/**
 * Total nominal dari semua additional_fee dalam satu row.
 */
export function additionalFeeTotal(additionalFee) {
    if (!Array.isArray(additionalFee)) return 0;
    return additionalFee.reduce((sum, f) => sum + (Number(f.nominal) || 0), 0);
}

/**
 * Total fee per row = fee + total additional fee.
 */
export function computeRowTotal(fee, additionalFee) {
    return roundToHundreds(fee + additionalFeeTotal(additionalFee));
}
