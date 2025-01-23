export default async function fetchData  (url,currentPage,recordsPerPage,searchQuery,id=null) {

    try {
        const response = await fetch(
            `${url}?page=${currentPage}&limit=${recordsPerPage}&search=${encodeURIComponent(
                searchQuery
            )}&id=${id}`
        );

        if (!response.ok) throw new Error("Failed to fetch data");

        const data = await response.json();
         return data;
    } catch (error) {
        console.error("Error fetching data:", error);
        return null;
    }
};

