export default function getUserId() {
    const meta = document.querySelector('meta[name="user"]');
    return meta ? meta.getAttribute('content') : '';
}
