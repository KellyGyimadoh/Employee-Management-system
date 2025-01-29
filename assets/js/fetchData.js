export default async function fetchData  (url,currentPage=null,
    recordsPerPage=null,searchQuery=null,id=null,date=null,status=null) {

    try {
        const response = await fetch(
            `${url}?page=${currentPage}&limit=${recordsPerPage}&search=${encodeURIComponent(
                searchQuery
            )}&id=${id}&searchdate=${date}&status=${status}`
        );

        if (!response.ok) throw new Error("Failed to fetch data");

        const data = await response.json();
         return data;
    } catch (error) {
        console.error("Error fetching data:", error);
        return null;
    }
};

