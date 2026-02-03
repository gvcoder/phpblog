const textarea = document.querySelector('textarea[name="content"]');

if (textarea) {
    const helper = document.createElement('small');
    helper.className = 'helper';
    helper.style.color = '#60708a';
    helper.style.display = 'block';
    helper.style.marginTop = '6px';

    const updateCount = () => {
        const words = textarea.value.trim().split(/\s+/).filter(Boolean);
        helper.textContent = `Word count: ${words.length}`;
    };

    textarea.addEventListener('input', updateCount);
    updateCount();
    textarea.parentElement.appendChild(helper);
}
