#ifndef phonebook_hpp_
#define phonebook_hpp_

#include <string>
#include <vector>
#include <list>
#include <map>
#include <sstream>
#include <algorithm>
#include <iomanip>
#include <functional>
#include <unordered_set>
#include <cstdlib>
#include <set>
#include <memory>
#include <cstring>
#include <unordered_map>

class phonebook
{
public:
	struct person_entry
	{
		~person_entry() {};

		using phone_list = std::vector< std::string>;

		std::string first_name;
		std::string last_name;
		std::string dept;
		phone_list phones;
	};

	using person_handle = std::shared_ptr<person_entry>; // storing shared_ptr of each person_entry -> allowing to add more person_entries with same attributes
	// access to any information in the person_entry is O(1) fast for given person_handle

	using person_handle_list = std::vector< person_handle>; // list of all handles

	using sorted_person_handle_lists = std::multimap<std::string, person_handle_list>;


	enum dept_filter
	{
		any_dept,
		dept_equal,
		dept_substring
	};

	enum last_name_filter
	{
		any_last_name,
		last_name_prefix
	};

	person_handle_list find(
		dept_filter p_dept,
		const std::string& s_dept,
		last_name_filter p_last_name,
		const std::string& s_last_name);

	const person_entry& get_person_entry(person_handle h) const; // requires logarithmic time search (std::set)

	person_handle add_person(const person_entry& z);

	void add_phone(person_handle h, const std::string& phone);

	void remove_phone(person_handle h, const std::string& phone);

	void change_last_name(person_handle h, const std::string& last_name);

	void change_dept(person_handle h, const std::string& dept);

	void remove_person(person_handle h);

	void add_person_name_database(person_handle z);

	void add_person_dept_database(person_handle z);

	void remove_person_name_database(person_handle z);

	void remove_person_dept_database(person_handle z);

	bool existence_check(person_handle z);

	bool contains_given_prefix(std::string base, const std::string& prefix);

private:
	sorted_person_handle_lists name_database; // key-> dept, value-> person_handle_list, storing all same last_names in vector, when deleting a handle, only deleting the entry in the vector
	// expecting not so many matches in last_names in the office

	std::unordered_map<std::string, sorted_person_handle_lists> dept_database; // key-> dept, value-> <last_name, vector of handles>
	// O(1) time for finding the dept, O(log n) time for searching the name in it.
};

#endif

/**/
