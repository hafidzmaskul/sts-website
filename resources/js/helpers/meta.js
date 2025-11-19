export function updatePageMeta({ title, description, keywords }) {
    if (typeof document === 'undefined') {
        return;
    }

    if (title) {
        document.title = title;
    }

    const setMeta = (name, content) => {
        if (!content) {
            return;
        }

        let tag = document.querySelector(`meta[name="${name}"]`);

        if (!tag) {
            tag = document.createElement('meta');
            tag.setAttribute('name', name);
            document.head.appendChild(tag);
        }

        tag.setAttribute('content', content);
    };

    setMeta('description', description);
    setMeta('keywords', keywords);
}

