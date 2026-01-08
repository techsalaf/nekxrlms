$('.dynamicColor').each(function() {
    let colorWords = $(this).data('color_word');

    if (colorWords) {
        let heading = $(this);
        let text = heading.text().split(' ');

        for (let i = 0; i < text.length; i++) {
            if (colorWords.includes(i + 1)) {
                text[i] = '<span class="text--base">' + text[i] + '</span>';
            }
        }

        heading.html(text.join(' '));
    }
});
