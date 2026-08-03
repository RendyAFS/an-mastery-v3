function trans(bag, key, replacements = {}) {
    const dict = window[bag] || {};
    let text = dict[key] ?? key;

    Object.entries(replacements).forEach(([k, v]) => {
        text = text.replaceAll(`:${k}`, v);
    });

    return text;
}

export default trans;
