export default async function fetchAll  (url) {

    try {
        const response = await fetch(
            `${url}`
        );

        if (!response.ok) throw new Error("Failed to fetch data");

        const data = await response.json();
         return data;
    } catch (error) {
        console.error("Error fetching data:", error);
        return null;
    }
};

