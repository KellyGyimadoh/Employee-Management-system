export default async function fetchUserId  (url,id) {

    try {
        const response = await fetch(
            `${url}?id=${id}`
        );

        if (!response.ok) throw new Error("Failed to fetch data");

        const data = await response.json();
         return data;
    } catch (error) {
        console.error("Error fetching data:", error);
        return null;
    }
};

